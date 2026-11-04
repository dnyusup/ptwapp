@extends('layouts.app')

@section('styles')
<style>
    .responsible-person-card {
        height: 100%;
    }

    .responsible-person-card .card-body {
        gap: 0.75rem;
    }

    .responsible-person-actions {
        flex: 0 0 auto;
    }

    .responsible-person-confirmation {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    .responsible-person-confirmation img {
        display: block;
        width: 120px;
        height: 36px;
        max-width: 100%;
        object-fit: contain;
        object-position: center;
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
    }

    .responsible-person-signed-on {
        white-space: nowrap;
    }

    #responsible-signature-canvas {
        display: block;
        width: 100%;
        height: 190px;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        background: #fff;
        touch-action: none;
    }

    @media (max-width: 575.98px) {
        .responsible-person-card .card-body {
            gap: 0.5rem;
        }

        .responsible-person-confirmation {
            gap: 0.35rem;
        }

        .responsible-person-confirmation img {
            width: 96px;
            height: 30px;
        }

        .responsible-person-signed-on {
            font-size: 0.7rem;
        }
    }
</style>
@endsection

@section('content')
@include('layouts.sidebar-styles')
@include('layouts.sidebar')

<!-- Main Content -->
<div class="main-content">
    @php
        $responsibleConfirmationVisible = in_array($permit->status, ['approved', 'active', 'in_progress', 'completed', 'expired'], true);
        $responsibleConfirmationEnabled = in_array($permit->status, ['approved', 'active', 'in_progress'], true);
    @endphp
    <div class="content-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-1"><i class="fas fa-file-alt me-2 text-primary"></i>Method Statement</h4>
                <p class="text-muted mb-0">Permit Number: {{ $permit->permit_number }}</p>
            </div>
            <div class="d-flex gap-2">
                @php
                    $canEdit = false;
                    $currentUser = auth()->user();
                    
                    // Administrator can always edit
                    if ($currentUser->role === 'administrator') {
                        $canEdit = true;
                    }
                    // Bekaert EHS department can edit
                    elseif ($currentUser->role === 'bekaert' && $currentUser->department === 'EHS') {
                        $canEdit = true;
                    }
                    // User as responsible person in permit
                    elseif ($permit->responsible_person === $currentUser->name) {
                        $canEdit = true;
                    }
                    // User as permit receiver
                    elseif ($permit->receiver_name === $currentUser->name) {
                        $canEdit = true;
                    }
                    // User as method statement creator
                    elseif ($methodStatement->created_by === $currentUser->id) {
                        $canEdit = true;
                    }
                    // User as responsible person in method statement (penanggung jawab)
                    elseif ($methodStatement->responsible_person_name === $currentUser->name) {
                        $canEdit = true;
                    }
                    // User as any of the responsible persons in the new array format
                    elseif ($methodStatement->responsible_persons && is_array($methodStatement->responsible_persons)) {
                        foreach ($methodStatement->responsible_persons as $person) {
                            if (isset($person['name']) && $person['name'] === $currentUser->name) {
                                $canEdit = true;
                                break;
                            }
                        }
                    }
                @endphp
                
                @if($canEdit)
                <a href="{{ route('method-statements.edit', $permit->permit_number) }}" class="btn btn-primary">
                    <i class="fas fa-edit me-2"></i>Edit
                </a>
                @endif
                
                <a href="{{ route('permits.show', $permit->id) }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Permit
                </a>
            </div>
        </div>
    </div>

    <!-- Status Badge -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-info">
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        <i class="fas fa-info-circle fa-2x"></i>
                    </div>
                    <div>
                        <h6 class="mb-1">Method Statement Status</h6>
                        <span class="badge bg-{{ $methodStatement->status === 'completed' ? 'success' : ($methodStatement->status === 'approved' ? 'primary' : 'secondary') }} fs-6">
                            {{ ucfirst($methodStatement->status) }}
                        </span>
                        <p class="mb-0 mt-2 text-muted">
                            Created: {{ $methodStatement->created_at->format('d M Y H:i') }} by {{ $methodStatement->creator->name ?? 'Unknown' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Basic Information -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Basic Information</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <strong>Nama Penanggung Jawab:</strong>
                    <p class="mb-0">{{ $methodStatement->responsible_person_name ?: 'Not specified' }}</p>
                </div>
                <div class="col-md-6 mb-3">
                    <strong>Tanggal Method Statement:</strong>
                    <p class="mb-0">{{ $methodStatement->method_statement_date ? $methodStatement->method_statement_date->format('d M Y') : 'Not specified' }}</p>
                </div>
                <div class="col-md-6 mb-3">
                    <strong>Nama Permit Receiver:</strong>
                    <p class="mb-0">{{ $methodStatement->permit_receiver_name ?: 'Not specified' }}</p>
                </div>
                <div class="col-md-6 mb-3">
                    <strong>Nama Pembuat Method Statement:</strong>
                    <p class="mb-0">{{ $methodStatement->permit_issuer_name ?: 'Not specified' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Responsible Persons -->
    @if($methodStatement->responsible_persons && count($methodStatement->responsible_persons) > 0)
    <div class="card mb-4">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0"><i class="fas fa-users me-2"></i>Responsible Persons</h5>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <label for="responsible-person-search" class="form-label">Cari nama responsible person</label>
                <input type="search" class="form-control" id="responsible-person-search" placeholder="Ketik nama untuk mencari..." autocomplete="off">
            </div>
            <div class="row">
                @foreach($methodStatement->responsible_persons as $index => $person)
                    @php
                        $user = null;
                        if (isset($person['email']) && isset($responsibleUsers)) {
                            $user = $responsibleUsers->get($person['email']);
                        }
                        $personEmail = $person['email'] ?? null;
                        $hasPersonEmail = is_string($personEmail) && trim($personEmail) !== '';
                        $confirmationKey = 'person:' . $index;
                        $confirmations = $methodStatement->responsible_person_confirmations ?? [];
                        $legacyConfirmationKey = $hasPersonEmail ? mb_strtolower(trim($personEmail)) : null;
                        $confirmation = $confirmations[$confirmationKey]
                            ?? ($legacyConfirmationKey ? ($confirmations[$legacyConfirmationKey] ?? null) : null);
                        $signatureImage = $confirmation && str_starts_with($confirmation['signature'], '<svg')
                            ? 'data:image/svg+xml,' . rawurlencode($confirmation['signature'])
                            : ($confirmation['signature'] ?? null);
                    @endphp
                    <div class="col-12 col-md-6 mb-3 responsible-person-item" data-person-name="{{ $person['name'] ?? '' }}">
                        <div class="card border-0 shadow-sm responsible-person-card">
                            <div class="card-body py-3 d-flex flex-column flex-sm-row align-items-sm-center justify-content-between">
                                <div class="flex-grow-1">
                                <h6 class="card-title mb-2">
                                    <span class="badge bg-primary me-2">{{ $index + 1 }}</span>
                                    {{ $person['name'] ?? 'Not specified' }}
                                </h6>
                                <p class="card-text small text-muted mb-0">
                                    <i class="fas fa-phone me-1"></i>
                                    {{ $user && $user->phone ? $user->phone : 'No phone number' }}
                                </p>
                                </div>
                                @if($responsibleConfirmationVisible)
                                <div class="responsible-person-actions">
                                    <div class="responsible-person-confirmation small" id="responsible-person-status-{{ $index }}">
                                        @if($confirmation)
                                            <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i>Confirmed</span>
                                            <img src="{{ $signatureImage }}" alt="Signature of {{ $person['name'] ?? 'responsible person' }}">
                                            <div class="text-muted responsible-person-signed-on">
                                                Signed on
                                                @if(!empty($confirmation['signed_at']))
                                                    {{ \Carbon\Carbon::parse($confirmation['signed_at'])->format('d M Y, H:i') }}
                                                @endif
                                            </div>
                                        @else
                                            <span class="badge bg-warning text-dark mb-2">Not confirmed</span>
                                            @if($responsibleConfirmationEnabled)
                                                <button type="button" class="btn btn-sm btn-outline-primary d-block" id="responsible-person-confirm-button-{{ $index }}"
                                                    data-bs-toggle="modal" data-bs-target="#responsible-signature-modal"
                                                    data-confirm-url="{{ route('method-statements.responsible-persons.confirm', [$permit->permit_number, $index]) }}"
                                                    data-person-name="{{ $person['name'] ?? 'Responsible person' }}">
                                                    <i class="fas fa-signature me-1"></i>Confirm
                                                </button>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="alert alert-light text-center mb-0 d-none" id="responsible-person-no-results">
                Tidak ada responsible person yang cocok dengan pencarian.
            </div>
        </div>
    </div>
    @endif

    @if($responsibleConfirmationEnabled)
    <div class="modal fade" id="responsible-signature-modal" tabindex="-1" aria-labelledby="responsible-signature-title" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <div>
                        <h5 class="modal-title" id="responsible-signature-title">Konfirmasi Responsible Person</h5>
                        <div class="small text-muted" id="responsible-signature-person"></div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <p class="small text-muted">Bubuhkan tanda tangan untuk mengonfirmasi bahwa Anda memahami Method Statement ini.</p>
                    <canvas id="responsible-signature-canvas" aria-label="Area tanda tangan"></canvas>
                    <div class="invalid-feedback d-block" id="responsible-signature-error" role="alert"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" id="responsible-signature-clear">Hapus</button>
                    <button type="button" class="btn btn-primary" id="responsible-signature-submit">
                        <i class="fas fa-check me-1"></i>Simpan konfirmasi
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Work Method Explanations -->
    <div class="card mb-4">
        <div class="card-header bg-secondary text-white">
            <h5 class="mb-0"><i class="fas fa-tools me-2"></i>Detail Langkah-Langkah Pekerjaan</h5>
        </div>
        <div class="card-body">
            @php
                $explanations = [
                    'safe_access_explanation' => 'Tentukan akses aman ke dan dari lokasi kerja, termasuk platform permanen, scaffolds (pegangan tangan, papan kaki, dll.), dan menara seluler. Dan bagaimana akses tanpa izin akan dicegah.',
                    'ppe_safety_equipment_explanation' => 'Tentukan APD dan peralatan keselamatan yang akan digunakan, dan kapan.',
                    'qualifications_training_explanation' => 'Cantumkan kualifikasi/pelatihan/pengalaman mereka yang diizinkan untuk melaksanakan pekerjaan tersebut dan pelatihan khusus apa pun untuk pekerjaan spesifik ini.',
                    'safe_routes_identification_explanation' => 'Mengidentifikasi rute akses aman untuk pejalan kaki, kendaraan, pabrik dan peralatan, dll.',
                    'storage_security_explanation' => 'Lokasi untuk penyimpanan peralatan dan material di luar pekerjaan dan pengaturan penandaan, penanganan, dan keamanan di tempat kerja.',
                    'equipment_checklist_explanation' => 'Buat daftar perlengkapan yang dibutuhkan, bagaimana perlengkapan tersebut akan disediakan, dan pemeriksaan apa saja yang perlu dilakukan, termasuk cranes, slings, dan lain-lain.',
                    'work_order_explanation' => 'Tentukan urutan pekerjaan.',
                    'temporary_work_explanation' => 'Jelaskan pekerjaan sementara yang akan disediakan dan tanggung jawab atas desain yang kompeten, misalnya scaffolding, trench supports, penyangga lantai sementara, dll.',
                    'weather_conditions_explanation' => 'Pertimbangan tentang dampak cuaca dan keterbatasan dalam bekerja dalam kondisi buruk.',
                    'area_maintenance_explanation' => 'Pengaturan untuk menjaga area kerja tetap bersih dan rapi, akomodasi sementara, dan area penyimpanan material.'
                ];
            @endphp
            
            @foreach($explanations as $field => $label)
                @if($methodStatement->$field)
                <div class="mb-4">
                    <h6 class="fw-bold text-primary">{{ $label }}</h6>
                    <p class="mb-0">{{ $methodStatement->$field }}</p>
                </div>
                @endif
            @endforeach
        </div>
    </div>

    <!-- Risk Assessment Table -->
    @if($methodStatement->risk_activities && count($methodStatement->risk_activities) > 0)
    <div class="card mb-4">
        <div class="card-header bg-danger text-white">
            <h5 class="mb-0"><i class="fas fa-exclamation-circle me-2"></i>Risk Assessment</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>Bahaya</th>
                            <th>Risiko</th>
                            <th>Tindakan Pengendalian</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($methodStatement->risk_activities as $index => $activity)
                        @if($activity)
                        <tr>
                            <td>{{ $activity }}</td>
                            <td>
                                @if(isset($methodStatement->risk_levels[$index]))
                                    @php
                                        $riskLevel = $methodStatement->risk_levels[$index];
                                        $badgeClass = '';
                                        $riskText = '';
                                        
                                        switch($riskLevel) {
                                            case 'P1':
                                                $badgeClass = 'bg-danger text-white';
                                                $riskText = 'Intolerable / Unacceptable Risk (P1)';
                                                break;
                                            case 'P2':
                                                $badgeClass = 'bg-danger text-white';
                                                $riskText = 'Intolerable / Unacceptable Risk (P2)';
                                                break;
                                            case 'P3':
                                                $badgeClass = 'bg-danger text-white';
                                                $riskText = 'Intolerable / Unacceptable Risk (P3)';
                                                break;
                                            case 'P4':
                                                $badgeClass = 'bg-warning text-dark';
                                                $riskText = 'Medium Risk - Look to reduce (P4)';
                                                break;
                                            case 'AR':
                                                $badgeClass = 'bg-success text-white';
                                                $riskText = 'Tolerable / Acceptable Risk (AR)';
                                                break;
                                            // Legacy support for old risk levels
                                            case 'High':
                                                $badgeClass = 'bg-danger text-white';
                                                $riskText = 'High Risk';
                                                break;
                                            case 'Medium':
                                                $badgeClass = 'bg-warning text-dark';
                                                $riskText = 'Medium Risk';
                                                break;
                                            case 'Low':
                                                $badgeClass = 'bg-success text-white';
                                                $riskText = 'Low Risk';
                                                break;
                                            default:
                                                $badgeClass = 'bg-secondary text-white';
                                                $riskText = $riskLevel;
                                        }
                                    @endphp
                                    <span class="badge {{ $badgeClass }} fw-bold">
                                        {{ $riskText }}
                                    </span>
                                @endif
                            </td>
                            <td>{{ $methodStatement->control_measures[$index] ?? '' }}</td>
                        </tr>
                        @endif
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="alert alert-warning mt-4">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <strong>Peringatan:</strong> Aktivitas yang diidentifikasi sebagai <strong>P1, P2, P3 (Intolerable/Unacceptable Risk)</strong> dan <strong>P4 (Medium Risk)</strong> membutuhkan tindakan pengendalian dan kontrol yang ketat.
            </div>
        </div>
    </div>
    @endif
</div>

@include('layouts.sidebar-scripts')
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('responsible-person-search');
    const personCards = Array.from(document.querySelectorAll('.responsible-person-item'));
    const noResults = document.getElementById('responsible-person-no-results');

    if (searchInput) {
        searchInput.addEventListener('input', function () {
            const query = this.value.trim().toLocaleLowerCase();
            let visibleCount = 0;

            personCards.forEach(function (card) {
                const matches = card.dataset.personName.toLocaleLowerCase().includes(query);
                card.classList.toggle('d-none', !matches);
                visibleCount += matches ? 1 : 0;
            });

            if (noResults) {
                noResults.classList.toggle('d-none', visibleCount > 0);
            }
        });
    }

    const modal = document.getElementById('responsible-signature-modal');
    if (!modal) {
        return;
    }

    const canvas = document.getElementById('responsible-signature-canvas');
    const context = canvas.getContext('2d');
    const submitButton = document.getElementById('responsible-signature-submit');
    const clearButton = document.getElementById('responsible-signature-clear');
    const errorMessage = document.getElementById('responsible-signature-error');
    const personLabel = document.getElementById('responsible-signature-person');
    let activeButton = null;
    let isDrawing = false;
    let hasSignature = false;
    let strokes = [];
    let currentStroke = null;

    function resizeCanvas() {
        const bounds = canvas.getBoundingClientRect();
        const pixelRatio = window.devicePixelRatio || 1;
        canvas.width = Math.round(bounds.width * pixelRatio);
        canvas.height = Math.round(bounds.height * pixelRatio);
        context.setTransform(pixelRatio, 0, 0, pixelRatio, 0, 0);
        context.lineWidth = 2.5;
        context.lineCap = 'round';
        context.lineJoin = 'round';
        context.strokeStyle = '#172554';
    }

    function clearSignature() {
        context.clearRect(0, 0, canvas.clientWidth, canvas.clientHeight);
        hasSignature = false;
        strokes = [];
        currentStroke = null;
        errorMessage.textContent = '';
    }

    function getCanvasPoint(event) {
        const bounds = canvas.getBoundingClientRect();
        const x = event.clientX - bounds.left;
        const y = event.clientY - bounds.top;
        return {
            x: x / bounds.width * 1000,
            y: y / bounds.height * 300,
            canvasX: x,
            canvasY: y
        };
    }

    modal.addEventListener('show.bs.modal', function (event) {
        activeButton = event.relatedTarget;
        personLabel.textContent = activeButton ? activeButton.dataset.personName : '';
        errorMessage.textContent = '';
    });

    modal.addEventListener('shown.bs.modal', function () {
        resizeCanvas();
        clearSignature();
    });

    canvas.addEventListener('pointerdown', function (event) {
        event.preventDefault();
        const point = getCanvasPoint(event);
        isDrawing = true;
        hasSignature = true;
        currentStroke = [{ x: point.x, y: point.y }];
        strokes.push(currentStroke);
        canvas.setPointerCapture(event.pointerId);
        context.beginPath();
        context.arc(point.canvasX, point.canvasY, 1.25, 0, Math.PI * 2);
        context.fillStyle = '#172554';
        context.fill();
        context.beginPath();
        context.moveTo(point.canvasX, point.canvasY);
    });

    canvas.addEventListener('pointermove', function (event) {
        if (!isDrawing) {
            return;
        }
        const point = getCanvasPoint(event);
        const previousPoint = currentStroke[currentStroke.length - 1];
        const previousCanvasX = previousPoint.x / 1000 * canvas.clientWidth;
        const previousCanvasY = previousPoint.y / 300 * canvas.clientHeight;
        if (Math.hypot(point.canvasX - previousCanvasX, point.canvasY - previousCanvasY) < 1) {
            return;
        }
        if (currentStroke.length < 250 && strokes.reduce((count, stroke) => count + stroke.length, 0) < 5000) {
            currentStroke.push({ x: point.x, y: point.y });
        }
        context.lineTo(point.canvasX, point.canvasY);
        context.stroke();
    });

    ['pointerup', 'pointercancel', 'lostpointercapture'].forEach(function (eventName) {
        canvas.addEventListener(eventName, function () {
            isDrawing = false;
            currentStroke = null;
        });
    });

    clearButton.addEventListener('click', clearSignature);

    submitButton.addEventListener('click', async function () {
        if (!activeButton || !hasSignature) {
            errorMessage.textContent = hasSignature ? '' : 'Tanda tangan wajib diisi.';
            return;
        }

        submitButton.disabled = true;
        errorMessage.textContent = '';

        try {
            const response = await fetch(activeButton.dataset.confirmUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ strokes: strokes })
            });
            const result = await response.json();

            if (!response.ok) {
                throw new Error(result.message || 'Konfirmasi gagal disimpan. Silakan coba lagi.');
            }

            const status = document.getElementById(activeButton.id.replace('confirm-button', 'status'));
            const badge = document.createElement('span');
            badge.className = 'badge bg-success mb-2';
            badge.innerHTML = '<i class="fas fa-check-circle me-1"></i>Confirmed';

            const signatureImage = document.createElement('img');
            signatureImage.src = result.confirmation.signature.startsWith('<svg')
                ? 'data:image/svg+xml,' + encodeURIComponent(result.confirmation.signature)
                : result.confirmation.signature;
            signatureImage.alt = 'Signature of ' + personLabel.textContent;

            const signer = document.createElement('div');
            signer.className = 'text-muted responsible-person-signed-on';
            signer.textContent = 'Signed on ' + new Date(result.confirmation.signed_at).toLocaleString();

            status.replaceChildren(badge, signatureImage, signer);
            activeButton.remove();
            bootstrap.Modal.getInstance(modal).hide();
        } catch (error) {
            errorMessage.textContent = error.message || 'Terjadi kesalahan saat menyimpan konfirmasi.';
        } finally {
            submitButton.disabled = false;
        }
    });
});
</script>
@endpush
