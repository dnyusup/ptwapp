@extends('layouts.app')

@section('content')
@include('layouts.sidebar-styles')
@include('layouts.sidebar')

<!-- Main Content -->
<div class="main-content">
    <div class="content-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-1"><i class="fas fa-shield-alt me-2 text-danger"></i>Create Emergency & Escape Plan</h4>
                <p class="text-muted mb-0">Permit Number: {{ $permit->permit_number }}</p>
            </div>
            <div>
                <a href="{{ route('permits.show', $permit->id) }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Permit
                </a>
            </div>
        </div>
    </div>

    <form action="{{ route('emergency-plans.store', $permit->permit_number) }}" method="POST">
        @csrf
                <!-- Show Validation Errors -->
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <h6><i class="fas fa-exclamation-triangle me-2"></i>Ada pertanyaan yang belum dijawab:</h6>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
                <!-- Emergency & Escape Plan Form -->
        <div class="card mb-4">
            <div class="card-header bg-danger text-white">
                <h5 class="mb-0"><i class="fas fa-shield-alt me-2"></i>Emergency & Escape Plan</h5>
            </div>
            <div class="card-body">
                <!-- Kemungkinan Kontaminasi -->
                <div class="row mb-4">
                    <label for="kontaminasi_keadaan" class="col-sm-4 col-form-label fw-bold">Kemungkinan kontaminan yang timbul:</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="kontaminasi_keadaan" name="kontaminasi_keadaan" value="{{ old('kontaminasi_keadaan') }}" placeholder="Jelaskan kemungkinan kontaminasi...">
                    </div>
                </div>

                <div class="mb-3">
                    <p class="fw-bold mb-3">Perencanaan Keadaan Darurat harus mencakup:</p>
                    
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-dark">
                                <tr>
                                    <th>Aspek Keselamatan</th>
                                    <th class="text-center" width="100">Ya</th>
                                    <th class="text-center" width="100">Tidak</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Petugas tanggap darurat memiliki kompetensi yang diperlukan, dan bukti yang tercatat</td>
                                    <td class="text-center">
                                        <input type="radio" class="form-check-input" name="petugas_tanggap_darurat" value="1" {{ old('petugas_tanggap_darurat') == '1' ? 'checked' : '' }}>
                                    </td>
                                    <td class="text-center">
                                        <input type="radio" class="form-check-input" name="petugas_tanggap_darurat" value="0" {{ old('petugas_tanggap_darurat') == '0' ? 'checked' : '' }}>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Cara meminta bantuan (komunikasi) tanpa meninggalkan ruang/area</td>
                                    <td class="text-center">
                                        <input type="radio" class="form-check-input" name="cara_meminta_bantuan" value="1" {{ old('cara_meminta_bantuan') == '1' ? 'checked' : '' }}>
                                    </td>
                                    <td class="text-center">
                                        <input type="radio" class="form-check-input" name="cara_meminta_bantuan" value="0" {{ old('cara_meminta_bantuan') == '0' ? 'checked' : '' }}>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Akses yang aman (tangga/perancah, dll.)</td>
                                    <td class="text-center">
                                        <input type="radio" class="form-check-input" name="sarana_akses_aman" value="1" {{ old('sarana_akses_aman') == '1' ? 'checked' : '' }}>
                                    </td>
                                    <td class="text-center">
                                        <input type="radio" class="form-check-input" name="sarana_akses_aman" value="0" {{ old('sarana_akses_aman') == '0' ? 'checked' : '' }}>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Korban bisa diselamatkan dengan aman, jika diperlukan</td>
                                    <td class="text-center">
                                        <input type="radio" class="form-check-input" name="orang_diselamatkan_aman" value="1" {{ old('orang_diselamatkan_aman') == '1' ? 'checked' : '' }}>
                                    </td>
                                    <td class="text-center">
                                        <input type="radio" class="form-check-input" name="orang_diselamatkan_aman" value="0" {{ old('orang_diselamatkan_aman') == '0' ? 'checked' : '' }}>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Kondisi housekeeping baik sehingga memungkinkan penyelamatan efektif dan efisien</td>
                                    <td class="text-center">
                                        <input type="radio" class="form-check-input" name="tata_graha_keadaan_baik" value="1" {{ old('tata_graha_keadaan_baik') == '1' ? 'checked' : '' }}>
                                    </td>
                                    <td class="text-center">
                                        <input type="radio" class="form-check-input" name="tata_graha_keadaan_baik" value="0" {{ old('tata_graha_keadaan_baik') == '0' ? 'checked' : '' }}>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Manual call point (breakglass) terdekat, eyewash, emergency shower, alat komunikasi</td>
                                    <td class="text-center">
                                        <input type="radio" class="form-check-input" name="lokasi_titik_panggilan" value="1" {{ old('lokasi_titik_panggilan') == '1' ? 'checked' : '' }}>
                                    </td>
                                    <td class="text-center">
                                        <input type="radio" class="form-check-input" name="lokasi_titik_panggilan" value="0" {{ old('lokasi_titik_panggilan') == '0' ? 'checked' : '' }}>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Petugas P3K yang terlatih</td>
                                    <td class="text-center">
                                        <input type="radio" class="form-check-input" name="ketersediaan_petugas_pertolongan" value="1" {{ old('ketersediaan_petugas_pertolongan') == '1' ? 'checked' : '' }}>
                                    </td>
                                    <td class="text-center">
                                        <input type="radio" class="form-check-input" name="ketersediaan_petugas_pertolongan" value="0" {{ old('ketersediaan_petugas_pertolongan') == '0' ? 'checked' : '' }}>
                                    </td>
                                </tr>
                                <tr>
                                    <td>AED tersedia dalam jangkauan 2 menit</td>
                                    <td class="text-center">
                                        <input type="radio" class="form-check-input" name="ketersediaan_defibrilator" value="1" {{ old('ketersediaan_defibrilator') == '1' ? 'checked' : '' }}>
                                    </td>
                                    <td class="text-center">
                                        <input type="radio" class="form-check-input" name="ketersediaan_defibrilator" value="0" {{ old('ketersediaan_defibrilator') == '0' ? 'checked' : '' }}>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Ketersediaan APAR yang sesuai, fire blanket, sprinkler, dll.</td>
                                    <td class="text-center">
                                        <input type="radio" class="form-check-input" name="ketersediaan_media_pemadam" value="1" {{ old('ketersediaan_media_pemadam') == '1' ? 'checked' : '' }}>
                                    </td>
                                    <td class="text-center">
                                        <input type="radio" class="form-check-input" name="ketersediaan_media_pemadam" value="0" {{ old('ketersediaan_media_pemadam') == '0' ? 'checked' : '' }}>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Kebutuhan Alat Pernapasan Khusus (respirator, SCBA)</td>
                                    <td class="text-center">
                                        <input type="radio" class="form-check-input" name="kebutuhan_alat_pernapasan" value="1" {{ old('kebutuhan_alat_pernapasan') == '1' ? 'checked' : '' }}>
                                    </td>
                                    <td class="text-center">
                                        <input type="radio" class="form-check-input" name="kebutuhan_alat_pernapasan" value="0" {{ old('kebutuhan_alat_pernapasan') == '0' ? 'checked' : '' }}>
                                    </td>
                                </tr>
                                <tr>
                                    <td>APD khusus lainnya</td>
                                    <td class="text-center">
                                        <input type="radio" class="form-check-input" name="apd_khusus_lainnya" value="1" {{ old('apd_khusus_lainnya') == '1' ? 'checked' : '' }}>
                                    </td>
                                    <td class="text-center">
                                        <input type="radio" class="form-check-input" name="apd_khusus_lainnya" value="0" {{ old('apd_khusus_lainnya') == '0' ? 'checked' : '' }}>
                                    </td>
                                </tr>
                                <tr id="apd_field" style="display: none;">
                                    <td colspan="3">
                                        <textarea class="form-control" id="sebutkan_apd" name="sebutkan_apd" rows="2" placeholder="Sebutkan APD khusus yang diperlukan...">{{ old('sebutkan_apd') }}</textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Tes gas diperlukan dan alat dikalibrasi?</td>
                                    <td class="text-center">
                                        <input type="radio" class="form-check-input" name="alat_ukur_gas_dikalibrasi" value="1" {{ old('alat_ukur_gas_dikalibrasi') == '1' ? 'checked' : '' }}>
                                    </td>
                                    <td class="text-center">
                                        <input type="radio" class="form-check-input" name="alat_ukur_gas_dikalibrasi" value="0" {{ old('alat_ukur_gas_dikalibrasi') == '0' ? 'checked' : '' }}>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Peralatan keselamatan khusus yang akan digunakan (tandu/harness/lifeline/hoist/lampu/dll.)</td>
                                    <td class="text-center">
                                        <input type="radio" class="form-check-input" name="peralatan_keselamatan_khusus" value="1" {{ old('peralatan_keselamatan_khusus') == '1' ? 'checked' : '' }}>
                                    </td>
                                    <td class="text-center">
                                        <input type="radio" class="form-check-input" name="peralatan_keselamatan_khusus" value="0" {{ old('peralatan_keselamatan_khusus') == '0' ? 'checked' : '' }}>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="alat_keselamatan_digunakan" class="form-label">Alat yang akan digunakan (mempertimbangkan persyaratan keselamatan):</label>
                    <textarea class="form-control" id="alat_keselamatan_digunakan" name="alat_keselamatan_digunakan" rows="3" placeholder="Jelaskan alat keselamatan yang akan digunakan...">{{ old('alat_keselamatan_digunakan') }}</textarea>
                </div>

                <div class="mb-3">
                    <label for="deskripsi_rencana_penyelamatan" class="form-label">Deskripsi rencana penyelamatan dan/atau referensi ke Rencana Darurat/Alarm di lokasi (termasuk
poin-poin dari atas) dan HARUS jelas peran/tanggung jawab per orang yang ditunjuk.</label>
                    <textarea class="form-control" id="deskripsi_rencana_penyelamatan" name="deskripsi_rencana_penyelamatan" rows="4" placeholder="Jelaskan rencana penyelamatan dan penanggung jawab yang terlibat...">{{ old('deskripsi_rencana_penyelamatan') }}</textarea>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <a href="{{ route('permits.show', $permit->id) }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times me-2"></i>Cancel
                    </a>
                    <div>
                        <button type="submit" name="save_draft" class="btn btn-outline-primary me-2">
                            <i class="fas fa-save me-2"></i>Save as Draft
                        </button>
                        <button type="submit" name="submit" class="btn btn-danger">
                            <i class="fas fa-check me-2"></i>Submit Emergency Plan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('emergencyPlanForm');
    
    // Get APD khusus lainnya radio buttons
    const apdRadios = document.querySelectorAll('input[name="apd_khusus_lainnya"]');
    const apdField = document.getElementById('apd_field');
    
    // Function to toggle APD field
    function toggleApdField() {
        const selectedValue = document.querySelector('input[name="apd_khusus_lainnya"]:checked');
        if (selectedValue && selectedValue.value === '1') {
            apdField.style.display = 'table-row';
        } else {
            apdField.style.display = 'none';
            // Clear the textarea when hiding
            document.getElementById('sebutkan_apd').value = '';
        }
    }
    
    // Add event listeners to radio buttons
    apdRadios.forEach(radio => {
        radio.addEventListener('change', toggleApdField);
    });
    
    // Initial check in case there's old input
    toggleApdField();
    
    // Form validation before submit
    if (form) {
        form.addEventListener('submit', function(e) {
            let isValid = true;
            let missingFields = [];
            
            // List of all radio button field names
            const requiredFields = [
                'petugas_tanggap_darurat', 'cara_meminta_bantuan', 'sarana_akses_aman', 'orang_diselamatkan_aman',
                'tata_graha_keadaan_baik', 'lokasi_titik_panggilan', 'ketersediaan_petugas_pertolongan', 
                'ketersediaan_defibrilator', 'ketersediaan_media_pemadam', 'kebutuhan_alat_pernapasan',
                'apd_khusus_lainnya', 'alat_ukur_gas_dikalibrasi', 'peralatan_keselamatan_khusus'
            ];
            
            // Check if all radio buttons are selected
            requiredFields.forEach(fieldName => {
                const radios = document.querySelectorAll(`input[name="${fieldName}"]`);
                const checked = document.querySelector(`input[name="${fieldName}"]:checked`);
                if (!checked) {
                    isValid = false;
                    // Get the field label from the table
                    const row = radios[0].closest('tr');
                    const label = row ? row.querySelector('td').textContent.trim() : fieldName;
                    missingFields.push(label);
                }
            });
            
            // Check APD field if APD khusus lainnya is "Ya"
            const apdKhususValue = document.querySelector('input[name="apd_khusus_lainnya"]:checked');
            if (apdKhususValue && apdKhususValue.value === '1') {
                const apdText = document.getElementById('sebutkan_apd').value.trim();
                if (!apdText) {
                    isValid = false;
                    missingFields.push('Sebutkan APD khusus');
                }
            }
            
            if (!isValid) {
                e.preventDefault();
                alert('Semua pertanyaan wajib dijawab!\n\nPertanyaan yang belum dijawab:\n- ' + missingFields.join('\n- '));
                return false;
            }
            
            return true;
        });
    }
});
</script>
@endsection