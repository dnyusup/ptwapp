@extends('layouts.app')

@section('content')
@include('layouts.sidebar-styles')
@include('layouts.sidebar')

<!-- Main Content -->
<div class="main-content">
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
            <div class="row">
                @foreach($methodStatement->responsible_persons as $index => $person)
                    @php
                        $user = null;
                        if (isset($person['email']) && isset($responsibleUsers)) {
                            $user = $responsibleUsers->get($person['email']);
                        }
                    @endphp
                    <div class="col-md-6 mb-3">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body py-3">
                                <h6 class="card-title mb-2">
                                    <span class="badge bg-primary me-2">{{ $index + 1 }}</span>
                                    {{ $person['name'] ?? 'Not specified' }}
                                </h6>
                                <p class="card-text small text-muted mb-0">
                                    <i class="fas fa-phone me-1"></i>
                                    {{ $user && $user->phone ? $user->phone : 'No phone number' }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endforeach
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
