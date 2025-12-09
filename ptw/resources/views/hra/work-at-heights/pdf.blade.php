<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>HRA Work at Heights - {{ $hraWorkAtHeight->hra_permit_number }}</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            margin: 15px; 
            color: black;
            font-size: 12px;
        }
        .header { 
            text-align: center; 
            margin-bottom: 15px;
            border-bottom: 2px solid black;
            padding-bottom: 8px;
        }
        .title { 
            font-size: 18px; 
            font-weight: bold; 
        }
        .section { 
            margin-bottom: 10px; 
            border: 1px solid black;
            padding: 8px;
        }
        .section-title { 
            font-weight: bold; 
            font-size: 14px;
            margin-bottom: 8px;
            background-color: #f5f5f5;
            padding: 4px;
            margin: -8px -8px 8px -8px;
        }
        .row { 
            margin-bottom: 3px; 
        }
        .label { 
            font-weight: bold; 
            display: inline-block;
            width: 170px;
        }
        .long-text-row {
            margin-bottom: 6px;
        }
        .long-text-label {
            font-weight: bold;
            display: block;
            margin-bottom: 2px;
        }
        .long-text-content {
            margin-left: 10px;
            line-height: 1.4;
            text-align: justify;
        }
        table { 
            width: 100%; 
            border-collapse: collapse; 
        }
        th, td { 
            border: 1px solid black; 
            padding: 3px; 
            text-align: left; 
        }
        th { 
            background-color: #f5f5f5; 
            font-weight: bold; 
        }
        .checkbox { 
            display: inline-block; 
            width: 12px; 
            height: 12px; 
            border: 1px solid black; 
            margin-right: 5px; 
            text-align: center;
            font-size: 10px;
            line-height: 10px;
        }
        .checked { 
            background-color: black; 
            color: white; 
        }
        .page-break { 
            page-break-before: always; 
        }
        .assessment-item {
            margin-bottom: 8px;
            padding: 4px;
            border: 1px solid #ddd;
        }
        .assessment-header {
            font-weight: bold;
            background-color: #f0f0f0;
            padding: 3px;
            margin-bottom: 5px;
        }
        .two-column {
            display: table;
            width: 100%;
        }
        .column {
            display: table-cell;
            width: 50%;
            vertical-align: top;
            padding-right: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div style="display: table; width: 100%; margin-bottom: 5px;">
            <div style="display: table-cell; width: 100px; vertical-align: top;">
                @if(isset($qrCode))
                    <img src="data:image/svg+xml;base64,{{ $qrCode }}" alt="QR Code" style="width: 80px; height: 80px;">
                @else
                    <div style="width: 80px; height: 80px; border: 2px solid black; text-align: center; font-size: 8px; padding: 5px; background: white;">
                        <div style="font-weight: bold; margin-bottom: 5px;">QR CODE</div>
                        <div style="font-size: 6px; line-height: 1.1;">Scan to view HRA details</div>
                        <div style="font-size: 5px; margin-top: 5px; word-wrap: break-word;">{{ $hraWorkAtHeight->hra_permit_number }}</div>
                    </div>
                @endif
            </div>
            <div style="display: table-cell; text-align: center; vertical-align: middle; padding-left: 20px;">
                <div class="title">HRA WORK AT HEIGHTS ASSESSMENT</div>
                <div style="font-size: 14px; margin-top: 5px;">{{ $hraWorkAtHeight->hra_permit_number }}</div>
                <div style="font-size: 10px; margin-top: 3px;">Main Permit: {{ $permit->permit_number }}</div>
            </div>
            <div style="display: table-cell; width: 100px; text-align: right; vertical-align: top; font-size: 10px;">
                <div style="font-weight: bold;">Generated:</div>
                <div>{{ date('d/m/Y H:i') }}</div>
            </div>
        </div>
    </div>

    <!-- Basic Information -->
    <div class="section">
        <div class="section-title">BASIC INFORMATION</div>
        <div class="two-column">
            <div class="column">
                <div class="row">
                    <span class="label">Worker Name:</span>
                    {{ $hraWorkAtHeight->worker_name ?? 'N/A' }}
                </div>
                <div class="row">
                    <span class="label">Phone Number:</span>
                    {{ $hraWorkAtHeight->worker_phone ?? 'N/A' }}
                </div>
                <div class="row">
                    <span class="label">Supervisor Name:</span>
                    {{ $hraWorkAtHeight->supervisor_name ?? 'N/A' }}
                </div>
                <div class="row">
                    <span class="label">Work Location:</span>
                    {{ $hraWorkAtHeight->work_location ?? 'N/A' }}
                </div>
            </div>
            <div class="column">
                <div class="row">
                    <span class="label">Start Time:</span>
                    {{ $hraWorkAtHeight->start_datetime ? \Carbon\Carbon::parse($hraWorkAtHeight->start_datetime)->format('d/m/Y H:i') : 'N/A' }}
                </div>
                <div class="row">
                    <span class="label">End Time:</span>
                    {{ $hraWorkAtHeight->end_datetime ? \Carbon\Carbon::parse($hraWorkAtHeight->end_datetime)->format('d/m/Y H:i') : 'N/A' }}
                </div>
                <div class="row">
                    <span class="label">Created By:</span>
                    {{ $hraWorkAtHeight->user->name ?? 'N/A' }}
                </div>
                <div class="row">
                    <span class="label">Created Date:</span>
                    {{ $hraWorkAtHeight->created_at->format('d/m/Y H:i') }}
                </div>
            </div>
        </div>
        <div class="long-text-row" style="margin-top: 8px;">
            <div class="long-text-label">Work Description:</div>
            <div class="long-text-content">{{ $hraWorkAtHeight->work_description ?? 'N/A' }}</div>
        </div>
    </div>

    <!-- HRA Assessment -->
    <div class="section">
        <div class="section-title">HRA WORK AT HEIGHTS ASSESSMENT</div>
        
        <div class="two-column">
            <div class="column">
                <!-- Overhead Hazards -->
                <div class="assessment-item">
                    <div class="assessment-header">Overhead Hazards</div>
                    <div class="row">
                        <span class="checkbox {{ $hraWorkAtHeight->overhead_hazards_checked ? 'checked' : '' }}">{{ $hraWorkAtHeight->overhead_hazards_checked ? '✓' : '' }}</span>
                        Layanan overhead/bahaya?
                    </div>
                    @if($hraWorkAtHeight->overhead_hazards_checked)
                    <div class="row" style="margin-left: 15px;">
                        <span class="checkbox {{ $hraWorkAtHeight->overhead_hazards_minimal_guards ? 'checked' : '' }}">{{ $hraWorkAtHeight->overhead_hazards_minimal_guards ? '✓' : '' }}</span>
                        Pengaman minimal untuk melindungi dari bahaya di atas
                    </div>
                    @endif
                </div>

                <!-- Fixed Scaffolding -->
                <div class="assessment-item">
                    <div class="assessment-header">Fixed Scaffolding</div>
                    <div class="row">
                        <span class="checkbox {{ $hraWorkAtHeight->fixed_scaffolding_checked ? 'checked' : '' }}">{{ $hraWorkAtHeight->fixed_scaffolding_checked ? '✓' : '' }}</span>
                        Fixed Scaffolding
                    </div>
                    @if($hraWorkAtHeight->fixed_scaffolding_checked)
                    <div class="row" style="margin-left: 15px;">
                        <span class="checkbox {{ $hraWorkAtHeight->fixed_scaffolding_approved_by_she ? 'checked' : '' }}">{{ $hraWorkAtHeight->fixed_scaffolding_approved_by_she ? '✓' : '' }}</span>
                        Sudah disetujui oleh SHE PTBI?
                    </div>
                    @endif
                </div>

                <!-- Mobile Elevated Working Platform (MEWP) -->
                <div class="assessment-item">
                    <div class="assessment-header">Mobile Elevated Working Platform (MEWP)</div>
                    <div class="row">
                        <span class="checkbox {{ $hraWorkAtHeight->mobile_elevation_checked ? 'checked' : '' }}">{{ $hraWorkAtHeight->mobile_elevation_checked ? '✓' : '' }}</span>
                        Mobile Elevated Working Platform (MEWP)
                    </div>
                    @if($hraWorkAtHeight->mobile_elevation_checked)
                    <div class="row" style="margin-left: 15px;">
                        <span class="checkbox {{ $hraWorkAtHeight->mobile_elevation_operator_trained ? 'checked' : '' }}">{{ $hraWorkAtHeight->mobile_elevation_operator_trained ? '✓' : '' }}</span>
                        Operator terlatih
                    </div>
                    <div class="row" style="margin-left: 15px;">
                        <span class="checkbox {{ $hraWorkAtHeight->mobile_elevation_rescue_person ? 'checked' : '' }}">{{ $hraWorkAtHeight->mobile_elevation_rescue_person ? '✓' : '' }}</span>
                        Orang yang berkompeten untuk melakukan penyelamatan
                    </div>
                    <div class="row" style="margin-left: 15px;">
                        <span class="checkbox {{ $hraWorkAtHeight->mobile_elevation_monitor_in_place ? 'checked' : '' }}">{{ $hraWorkAtHeight->mobile_elevation_monitor_in_place ? '✓' : '' }}</span>
                        Pemantau di tempat untuk semua pergerakan MEWP
                    </div>
                    <div class="row" style="margin-left: 15px;">
                        <span class="checkbox {{ $hraWorkAtHeight->mobile_elevation_legal_inspection ? 'checked' : '' }}">{{ $hraWorkAtHeight->mobile_elevation_legal_inspection ? '✓' : '' }}</span>
                        Catatan pemeriksaan hukum valid
                    </div>
                    <div class="row" style="margin-left: 15px;">
                        <span class="checkbox {{ $hraWorkAtHeight->mobile_elevation_pre_use_inspection ? 'checked' : '' }}">{{ $hraWorkAtHeight->mobile_elevation_pre_use_inspection ? '✓' : '' }}</span>
                        Pemeriksaan pra-penggunaan yang terdokumentasi telah selesai
                    </div>
                    @endif
                </div>

                <!-- Fall Arrest -->
                <div class="assessment-item">
                    <div class="assessment-header">APD WAH</div>
                    <div class="row">
                        <span class="checkbox {{ $hraWorkAtHeight->fall_arrest_used ? 'checked' : '' }}">{{ $hraWorkAtHeight->fall_arrest_used ? '✓' : '' }}</span>
                        APD WAH diperlukan? (peralatan penangkap dan penahan jatuh)
                    </div>
                    @if($hraWorkAtHeight->fall_arrest_used)
                    <div class="row" style="margin-left: 15px;">
                        <span class="checkbox {{ $hraWorkAtHeight->fall_arrest_worker_trained ? 'checked' : '' }}">{{ $hraWorkAtHeight->fall_arrest_worker_trained ? '✓' : '' }}</span>
                        Pekerja yang terlatih dalam penggunaan
                    </div>
                    <div class="row" style="margin-left: 15px;">
                        <span class="checkbox {{ $hraWorkAtHeight->fall_arrest_legal_inspection ? 'checked' : '' }}">{{ $hraWorkAtHeight->fall_arrest_legal_inspection ? '✓' : '' }}</span>
                        Catatan pemeriksaan hukum valid
                    </div>
                    <div class="row" style="margin-left: 15px;">
                        <span class="checkbox {{ $hraWorkAtHeight->fall_arrest_pre_use_inspection ? 'checked' : '' }}">{{ $hraWorkAtHeight->fall_arrest_pre_use_inspection ? '✓' : '' }}</span>
                        Pemeriksaan pra-penggunaan
                    </div>
                    <div class="row" style="margin-left: 15px;">
                        <span class="checkbox {{ $hraWorkAtHeight->fall_arrest_qualified_personnel ? 'checked' : '' }}">{{ $hraWorkAtHeight->fall_arrest_qualified_personnel ? '✓' : '' }}</span>
                        Sarana pengikatan yang ditentukan oleh personel yang berkualifikasi
                    </div>
                    @endif
                </div>
            </div>

            <div class="column">
                <!-- Mobile Scaffolding -->
                <div class="assessment-item">
                    <div class="assessment-header">Mobile Scaffolding</div>
                    <div class="row">
                        <span class="checkbox {{ $hraWorkAtHeight->mobile_scaffolding_checked ? 'checked' : '' }}">{{ $hraWorkAtHeight->mobile_scaffolding_checked ? '✓' : '' }}</span>
                        Mobile scaffolding
                    </div>
                    @if($hraWorkAtHeight->mobile_scaffolding_checked)
                    <div class="row" style="margin-left: 15px;">
                        <span class="checkbox {{ $hraWorkAtHeight->mobile_scaffolding_approved_by_she ? 'checked' : '' }}">{{ $hraWorkAtHeight->mobile_scaffolding_approved_by_she ? '✓' : '' }}</span>
                        Sudah disetujui oleh SHE PTBI?
                    </div>
                    @endif
                </div>

                <!-- Tangga -->
                <div class="assessment-item">
                    <div class="assessment-header">Tangga</div>
                    <div class="row">
                        <span class="checkbox {{ $hraWorkAtHeight->ladder_checked ? 'checked' : '' }}">{{ $hraWorkAtHeight->ladder_checked ? '✓' : '' }}</span>
                        Tangga
                    </div>
                    <div class="row">
                        <span class="checkbox {{ $hraWorkAtHeight->mobile_elevation_activities_short ? 'checked' : '' }}">{{ $hraWorkAtHeight->mobile_elevation_activities_short ? '✓' : '' }}</span>
                        Gunakan untuk aktivitas jangka pendek dengan potensi bahaya minor
                    </div>
                    <div class="row">
                        <span class="checkbox {{ $hraWorkAtHeight->ladder_area_barriers ? 'checked' : '' }}">{{ $hraWorkAtHeight->ladder_area_barriers ? '✓' : '' }}</span>
                        Dipericsa dan di-tag
                    </div>
                </div>

                <!-- Roof Work -->
                <div class="assessment-item">
                    <div class="assessment-header">Roof Work</div>
                    <div class="row">
                        <span class="checkbox {{ $hraWorkAtHeight->roof_work_checked ? 'checked' : '' }}">{{ $hraWorkAtHeight->roof_work_checked ? '✓' : '' }}</span>
                        Pekerjaan di Atap (Roof Work)
                    </div>
                    @if($hraWorkAtHeight->roof_work_checked)
                    <div class="row" style="margin-left: 15px;">
                        <span class="checkbox {{ $hraWorkAtHeight->roof_load_capacity_adequate ? 'checked' : '' }}">{{ $hraWorkAtHeight->roof_load_capacity_adequate ? '✓' : '' }}</span>
                        Kapasitas menahan beban atap cukup
                    </div>
                    <div class="row" style="margin-left: 15px;">
                        <span class="checkbox {{ $hraWorkAtHeight->roof_edge_protection ? 'checked' : '' }}">{{ $hraWorkAtHeight->roof_edge_protection ? '✓' : '' }}</span>
                        Penggunaan perlindungan tepi
                    </div>
                    <div class="row" style="margin-left: 15px;">
                        <span class="checkbox {{ $hraWorkAtHeight->roof_fall_protection_system ? 'checked' : '' }}">{{ $hraWorkAtHeight->roof_fall_protection_system ? '✓' : '' }}</span>
                        Penggunaan sistem perlindungan jatuh/ WaH PPE
                    </div>
                    <div class="row" style="margin-left: 15px;">
                        <span class="checkbox {{ $hraWorkAtHeight->roof_communication_method ? 'checked' : '' }}">{{ $hraWorkAtHeight->roof_communication_method ? '✓' : '' }}</span>
                        Metode komunikasi yang disepakati
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Work Conditions -->
    <div class="section">
        <div class="section-title">WORK CONDITIONS</div>
        <div class="two-column">
            <div class="column">
                <div class="row">
                    <span class="checkbox {{ $hraWorkAtHeight->workers_have_training_proof ? 'checked' : '' }}">{{ $hraWorkAtHeight->workers_have_training_proof ? '✓' : '' }}</span>
                    Apakah mereka yang terlibat memiliki bukti Pelatihan Bekerja di Ketinggian
                </div>
                <div class="row">
                    <span class="checkbox {{ $hraWorkAtHeight->area_below_blocked ? 'checked' : '' }}">{{ $hraWorkAtHeight->area_below_blocked ? '✓' : '' }}</span>
                    Apakah area di bawah tempat kerja telah diblokir untuk kendaraan/lalu lintas/pejalan kaki
                </div>
                <div class="row">
                    <span class="checkbox {{ $hraWorkAtHeight->workers_below_present ? 'checked' : '' }}">{{ $hraWorkAtHeight->workers_below_present ? '✓' : '' }}</span>
                    Apakah ada yang bekerja di bawah mereka yang bekerja di ketinggian
                </div>
                <div class="row">
                    <span class="checkbox {{ $hraWorkAtHeight->floor_suitable_for_access_equipment ? 'checked' : '' }}">{{ $hraWorkAtHeight->floor_suitable_for_access_equipment ? '✓' : '' }}</span>
                    Apakah lantai/tanah cocok untuk digunakannya peralatan akses
                </div>
                <div class="row">
                    <span class="checkbox {{ $hraWorkAtHeight->obstacles_near_work_location ? 'checked' : '' }}">{{ $hraWorkAtHeight->obstacles_near_work_location ? '✓' : '' }}</span>
                    Apakah ada kendala di atau dekat lokasi kerja (saluran kabel, kabel tunggal, pipa, dll.)
                </div>
            </div>
            <div class="column">
                <div class="row">
                    <span class="checkbox {{ $hraWorkAtHeight->ventilation_hazardous_emissions ? 'checked' : '' }}">{{ $hraWorkAtHeight->ventilation_hazardous_emissions ? '✓' : '' }}</span>
                    Apakah ada ventilasi, cerobong asapyang dapat mengeluarkan media panas/berbau/berbahaya
                </div>
                <div class="row">
                    <span class="checkbox {{ $hraWorkAtHeight->protection_needed_for_equipment ? 'checked' : '' }}">{{ $hraWorkAtHeight->protection_needed_for_equipment ? '✓' : '' }}</span>
                    Apakah perlindungan dibutuhkan untuk peralatan akses WaH dan/atau peralatan proses/pabrik di lokasi
                </div>
                <div class="row">
                    <span class="checkbox {{ $hraWorkAtHeight->safe_access_exit_method ? 'checked' : '' }}">{{ $hraWorkAtHeight->safe_access_exit_method ? '✓' : '' }}</span>
                    Apakah ada metode akses & keluar yang aman
                </div>
                <div class="row">
                    <span class="checkbox {{ $hraWorkAtHeight->safe_material_handling_method ? 'checked' : '' }}">{{ $hraWorkAtHeight->safe_material_handling_method ? '✓' : '' }}</span>
                    Apakah cara yang aman untuk menaik turunkan material dan peralatan telah ditentukan
                </div>
                <div class="row">
                    <span class="checkbox {{ $hraWorkAtHeight->emergency_escape_plan_needed ? 'checked' : '' }}">{{ $hraWorkAtHeight->emergency_escape_plan_needed ? '✓' : '' }}</span>
                    Apakah diperlukan rencana darurat & pelarian
                </div>
                <div class="row">
                    <span class="checkbox {{ $hraWorkAtHeight->other_conditions_check ? 'checked' : '' }}">{{ $hraWorkAtHeight->other_conditions_check ? '✓' : '' }}</span>
                    Lainnya
                </div>
            </div>
        </div>
        @if($hraWorkAtHeight->other_conditions_check && $hraWorkAtHeight->other_conditions_text)
        <div class="long-text-row">
            <div class="long-text-label">Keterangan kondisi lain:</div>
            <div class="long-text-content">{{ $hraWorkAtHeight->other_conditions_text }}</div>
        </div>
        @endif
    </div>

    <!-- Environmental Conditions -->
    <div class="section">
        <div class="section-title">ENVIRONMENTAL CONDITIONS</div>
        <table style="margin-bottom: 10px;">
            <tr>
                <td style="width: 45%; font-weight: bold; background-color: #f8f9fa;">Jarak pandang umum:</td>
                <td style="width: 25%; text-align: center;">{{ $hraWorkAtHeight->visibility_condition ?? 'Not Set' }}</td>
                <td style="width: 15%; font-weight: bold; background-color: #f8f9fa;">Hujan:</td>
                <td style="width: 15%; text-align: center;">{{ $hraWorkAtHeight->rain_condition ?? 'Not Set' }}</td>
            </tr>
            <tr>
                <td style="font-weight: bold; background-color: #f8f9fa;">Kondisi permukaan tanah/lantai:</td>
                <td style="text-align: center;">{{ $hraWorkAtHeight->surface_condition ?? 'Not Set' }}</td>
                <td style="font-weight: bold; background-color: #f8f9fa;">Angin:</td>
                <td style="text-align: center;">{{ $hraWorkAtHeight->wind_condition ?? 'Not Set' }}</td>
            </tr>
            <tr>
                <td style="font-weight: bold; background-color: #f8f9fa;">Permukaan licin dari tumpahan oli atau bahan kimia?:</td>
                <td style="text-align: center;">{{ $hraWorkAtHeight->chemical_spill_condition ?? 'Not Set' }}</td>
                <td colspan="2"></td>
            </tr>
        </table>
        @if($hraWorkAtHeight->environment_other_conditions)
        <div class="long-text-row">
            <div class="long-text-label">Lainnya:</div>
            <div class="long-text-content">{{ $hraWorkAtHeight->environment_other_conditions }}</div>
        </div>
        @endif
    </div>

    <!-- Additional Controls -->
    @if($hraWorkAtHeight->additional_controls)
    <div class="section">
        <div class="section-title">PENGENDALIAN TAMBAHAN</div>
        <div class="long-text-content">{{ $hraWorkAtHeight->additional_controls }}</div>
    </div>
    @endif

    <!-- Main Permit Information -->
    <div class="section">
        <div class="section-title">MAIN PERMIT INFORMATION</div>
        <div style="display: table; width: 100%;">
            <div style="display: table-cell; width: 70%; vertical-align: top; padding-right: 15px;">
                <div class="row">
                    <span class="label">Permit Number:</span>
                    {{ $permit->permit_number }}
                </div>
                <div class="row">
                    <span class="label">Work Title:</span>
                    {{ $permit->work_title }}
                </div>
                <div class="row">
                    <span class="label">Status:</span>
                    {{ strtoupper($permit->status) }}
                </div>
                <div class="row">
                    <span class="label">Receiver Company:</span>
                    {{ $permit->receiver_company_name ?? 'N/A' }}
                </div>
            </div>
            <div style="display: table-cell; width: 30%; vertical-align: top; text-align: center;">
                @if(isset($permitQrCode))
                    <img src="data:image/svg+xml;base64,{{ $permitQrCode }}" alt="Main Permit QR Code" style="width: 70px; height: 70px; margin-bottom: 5px;">
                @else
                    <div style="width: 70px; height: 70px; border: 2px solid black; text-align: center; font-size: 7px; padding: 3px; background: white; margin: 0 auto 5px;">
                        <div style="font-weight: bold; margin-bottom: 3px;">QR CODE</div>
                        <div style="font-size: 5px; line-height: 1.1;">Main Permit</div>
                        <div style="font-size: 4px; margin-top: 3px; word-wrap: break-word;">{{ $permit->permit_number }}</div>
                    </div>
                @endif
                <div style="font-size: 8px; font-weight: bold; margin-bottom: 2px;">Main Permit</div>
                <div style="font-size: 7px; line-height: 1.2;">Scan to view<br>main permit details</div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div style="margin-top: 20px; text-align: center; font-size: 10px; color: #666;">
        <div>Generated on {{ date('d/m/Y H:i:s') }}</div>
        <div>HRA Work at Heights Assessment - {{ $hraWorkAtHeight->hra_permit_number }}</div>
    </div>
</body>
</html>
