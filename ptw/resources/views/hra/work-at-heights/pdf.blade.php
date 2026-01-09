<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>HRA Work at Heights - {{ $hraWorkAtHeight->hra_permit_number }}</title>
    <style>
        @page { margin: 10mm; }
        body { 
            font-family: Arial, sans-serif; 
            margin: 0; 
            color: black;
            font-size: 9px;
            line-height: 1.25;
        }
        .header { 
            text-align: center; 
            margin-bottom: 8px;
            border-bottom: 2px solid black;
            padding-bottom: 6px;
        }
        .title { 
            font-size: 14px; 
            font-weight: bold; 
        }
        .section { 
            margin-bottom: 6px; 
            border: 1px solid black;
            padding: 6px;
        }
        .section-title { 
            font-weight: bold; 
            font-size: 10px;
            margin-bottom: 4px;
            background-color: #e0e0e0;
            padding: 3px 5px;
            margin: -6px -6px 6px -6px;
        }
        .row { 
            margin-bottom: 2px; 
        }
        .label { 
            font-weight: bold; 
            display: inline-block;
            width: 100px;
        }
        .long-text-label {
            font-weight: bold;
            display: inline;
        }
        .long-text-content {
            display: inline;
        }
        table { 
            width: 100%; 
            border-collapse: collapse; 
        }
        th, td { 
            border: 1px solid black; 
            padding: 3px; 
            text-align: left; 
            font-size: 8px;
        }
        th { 
            background-color: #e0e0e0; 
            font-weight: bold; 
        }
        .checkbox { 
            display: inline-block; 
            width: 10px; 
            height: 10px; 
            border: 1px solid black; 
            margin-right: 3px; 
            text-align: center;
            font-size: 8px;
            line-height: 9px;
            vertical-align: middle;
        }
        .checked { 
            background-color: black; 
            color: white; 
        }
        .assessment-item {
            margin-bottom: 4px;
            padding: 3px;
            border: 1px solid #ccc;
        }
        .assessment-header {
            font-weight: bold;
            background-color: #f0f0f0;
            padding: 2px 4px;
            margin-bottom: 3px;
            font-size: 9px;
        }
        .two-column {
            display: table;
            width: 100%;
        }
        .column {
            display: table-cell;
            width: 50%;
            vertical-align: top;
            padding-right: 5px;
        }
        .three-column {
            display: table;
            width: 100%;
        }
        .col-third {
            display: table-cell;
            width: 33.33%;
            vertical-align: top;
            padding-right: 4px;
        }
        .sub-row {
            margin-left: 12px;
            font-size: 8px;
            margin-bottom: 1px;
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
                <div style="font-size: 12px; margin-top: 5px;">{{ $hraWorkAtHeight->hra_permit_number }}</div>
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
                <div class="row"><span class="label">Worker Name:</span> {{ $hraWorkAtHeight->worker_name ?? 'N/A' }}</div>
                <div class="row"><span class="label">Phone Number:</span> {{ $hraWorkAtHeight->worker_phone ?? 'N/A' }}</div>
                <div class="row"><span class="label">Supervisor:</span> {{ $hraWorkAtHeight->supervisor_name ?? 'N/A' }}</div>
                <div class="row"><span class="label">Work Location:</span> {{ $hraWorkAtHeight->work_location ?? 'N/A' }}</div>
            </div>
            <div class="column">
                <div class="row"><span class="label">Start Time:</span> {{ $hraWorkAtHeight->start_datetime ? \Carbon\Carbon::parse($hraWorkAtHeight->start_datetime)->format('d/m/Y H:i') : 'N/A' }}</div>
                <div class="row"><span class="label">End Time:</span> {{ $hraWorkAtHeight->end_datetime ? \Carbon\Carbon::parse($hraWorkAtHeight->end_datetime)->format('d/m/Y H:i') : 'N/A' }}</div>
                <div class="row"><span class="label">Created By:</span> {{ $hraWorkAtHeight->user->name ?? 'N/A' }}</div>
                <div class="row"><span class="label">Created Date:</span> {{ $hraWorkAtHeight->created_at->format('d/m/Y H:i') }}</div>
            </div>
        </div>
        <div class="row" style="margin-top: 4px;"><span class="long-text-label">Work Description:</span> <span class="long-text-content">{{ $hraWorkAtHeight->work_description ?? 'N/A' }}</span></div>
    </div>

    <!-- HRA Assessment -->
    <div class="section">
        <div class="section-title">HRA WORK AT HEIGHTS ASSESSMENT</div>
        <div class="two-column">
            <!-- Column 1 -->
            <div class="column">
                <div class="assessment-item">
                    <div class="assessment-header">Overhead Hazards</div>
                    <div class="row"><span class="checkbox {{ $hraWorkAtHeight->overhead_hazards_checked ? 'checked' : '' }}">{{ $hraWorkAtHeight->overhead_hazards_checked ? 'v' : '' }}</span> Layanan overhead/bahaya?</div>
                    @if($hraWorkAtHeight->overhead_hazards_checked)
                    <div class="sub-row"><span class="checkbox {{ $hraWorkAtHeight->overhead_hazards_minimal_guards ? 'checked' : '' }}">{{ $hraWorkAtHeight->overhead_hazards_minimal_guards ? 'v' : '' }}</span> Pengaman minimal untuk melindungi dari bahaya di atas</div>
                    @endif
                </div>

                <div class="assessment-item">
                    <div class="assessment-header">Fixed Scaffolding</div>
                    <div class="row"><span class="checkbox {{ $hraWorkAtHeight->fixed_scaffolding_checked ? 'checked' : '' }}">{{ $hraWorkAtHeight->fixed_scaffolding_checked ? 'v' : '' }}</span> Fixed Scaffolding</div>
                    @if($hraWorkAtHeight->fixed_scaffolding_checked)
                    <div class="sub-row"><span class="checkbox {{ $hraWorkAtHeight->fixed_scaffolding_approved_by_she ? 'checked' : '' }}">{{ $hraWorkAtHeight->fixed_scaffolding_approved_by_she ? 'v' : '' }}</span> Sudah disetujui oleh SHE PTBI?</div>
                    @endif
                </div>

                <div class="assessment-item">
                    <div class="assessment-header">Mobile Scaffolding</div>
                    <div class="row"><span class="checkbox {{ $hraWorkAtHeight->mobile_scaffolding_checked ? 'checked' : '' }}">{{ $hraWorkAtHeight->mobile_scaffolding_checked ? 'v' : '' }}</span> Mobile scaffolding</div>
                    @if($hraWorkAtHeight->mobile_scaffolding_checked)
                    <div class="sub-row"><span class="checkbox {{ $hraWorkAtHeight->mobile_scaffolding_approved_by_she ? 'checked' : '' }}">{{ $hraWorkAtHeight->mobile_scaffolding_approved_by_she ? 'v' : '' }}</span> Sudah disetujui oleh SHE PTBI?</div>
                    @endif
                </div>

                <div class="assessment-item">
                    <div class="assessment-header">Mobile Elevated Working Platform (MEWP)</div>
                    <div class="row"><span class="checkbox {{ $hraWorkAtHeight->mobile_elevation_checked ? 'checked' : '' }}">{{ $hraWorkAtHeight->mobile_elevation_checked ? 'v' : '' }}</span> Mobile Elevated Working Platform (MEWP)</div>
                    @if($hraWorkAtHeight->mobile_elevation_checked)
                    <div class="sub-row"><span class="checkbox {{ $hraWorkAtHeight->mobile_elevation_operator_trained ? 'checked' : '' }}">{{ $hraWorkAtHeight->mobile_elevation_operator_trained ? 'v' : '' }}</span> Operator terlatih</div>
                    <div class="sub-row"><span class="checkbox {{ $hraWorkAtHeight->mobile_elevation_rescue_person ? 'checked' : '' }}">{{ $hraWorkAtHeight->mobile_elevation_rescue_person ? 'v' : '' }}</span> Orang yang berkompeten untuk melakukan penyelamatan</div>
                    <div class="sub-row"><span class="checkbox {{ $hraWorkAtHeight->mobile_elevation_monitor_in_place ? 'checked' : '' }}">{{ $hraWorkAtHeight->mobile_elevation_monitor_in_place ? 'v' : '' }}</span> Pemantau di tempat untuk semua pergerakan MEWP</div>
                    <div class="sub-row"><span class="checkbox {{ $hraWorkAtHeight->mobile_elevation_legal_inspection ? 'checked' : '' }}">{{ $hraWorkAtHeight->mobile_elevation_legal_inspection ? 'v' : '' }}</span> Catatan pemeriksaan hukum valid</div>
                    <div class="sub-row"><span class="checkbox {{ $hraWorkAtHeight->mobile_elevation_pre_use_inspection ? 'checked' : '' }}">{{ $hraWorkAtHeight->mobile_elevation_pre_use_inspection ? 'v' : '' }}</span> Pemeriksaan pra-penggunaan yang terdokumentasi telah selesai</div>
                    @endif
                </div>
            </div>

            <!-- Column 2 -->
            <div class="column">
                <div class="assessment-item">
                    <div class="assessment-header">Tangga</div>
                    <div class="row"><span class="checkbox {{ $hraWorkAtHeight->ladder_checked ? 'checked' : '' }}">{{ $hraWorkAtHeight->ladder_checked ? 'v' : '' }}</span> Tangga</div>
                    <div class="sub-row"><span class="checkbox {{ $hraWorkAtHeight->mobile_elevation_activities_short ? 'checked' : '' }}">{{ $hraWorkAtHeight->mobile_elevation_activities_short ? 'v' : '' }}</span> Gunakan untuk aktivitas jangka pendek dengan potensi bahaya minor</div>
                    <div class="sub-row"><span class="checkbox {{ $hraWorkAtHeight->ladder_area_barriers ? 'checked' : '' }}">{{ $hraWorkAtHeight->ladder_area_barriers ? 'v' : '' }}</span> Diperiksa dan di-tag</div>
                </div>

                <div class="assessment-item">
                    <div class="assessment-header">APD WAH (Fall Arrest)</div>
                    <div class="row"><span class="checkbox {{ $hraWorkAtHeight->fall_arrest_used ? 'checked' : '' }}">{{ $hraWorkAtHeight->fall_arrest_used ? 'v' : '' }}</span> APD WAH diperlukan? (peralatan penangkap dan penahan jatuh)</div>
                    @if($hraWorkAtHeight->fall_arrest_used)
                    <div class="sub-row"><span class="checkbox {{ $hraWorkAtHeight->fall_arrest_worker_trained ? 'checked' : '' }}">{{ $hraWorkAtHeight->fall_arrest_worker_trained ? 'v' : '' }}</span> Pekerja yang terlatih dalam penggunaan</div>
                    <div class="sub-row"><span class="checkbox {{ $hraWorkAtHeight->fall_arrest_legal_inspection ? 'checked' : '' }}">{{ $hraWorkAtHeight->fall_arrest_legal_inspection ? 'v' : '' }}</span> Catatan pemeriksaan hukum valid</div>
                    <div class="sub-row"><span class="checkbox {{ $hraWorkAtHeight->fall_arrest_pre_use_inspection ? 'checked' : '' }}">{{ $hraWorkAtHeight->fall_arrest_pre_use_inspection ? 'v' : '' }}</span> Pemeriksaan pra-penggunaan</div>
                    <div class="sub-row"><span class="checkbox {{ $hraWorkAtHeight->fall_arrest_qualified_personnel ? 'checked' : '' }}">{{ $hraWorkAtHeight->fall_arrest_qualified_personnel ? 'v' : '' }}</span> Sarana pengikatan yang ditentukan oleh personel yang berkualifikasi</div>
                    @endif
                </div>

                <div class="assessment-item">
                    <div class="assessment-header">Roof Work</div>
                    <div class="row"><span class="checkbox {{ $hraWorkAtHeight->roof_work_checked ? 'checked' : '' }}">{{ $hraWorkAtHeight->roof_work_checked ? 'v' : '' }}</span> Pekerjaan di Atap (Roof Work)</div>
                    @if($hraWorkAtHeight->roof_work_checked)
                    <div class="sub-row"><span class="checkbox {{ $hraWorkAtHeight->roof_load_capacity_adequate ? 'checked' : '' }}">{{ $hraWorkAtHeight->roof_load_capacity_adequate ? 'v' : '' }}</span> Kapasitas menahan beban atap cukup</div>
                    <div class="sub-row"><span class="checkbox {{ $hraWorkAtHeight->roof_edge_protection ? 'checked' : '' }}">{{ $hraWorkAtHeight->roof_edge_protection ? 'v' : '' }}</span> Penggunaan perlindungan tepi</div>
                    <div class="sub-row"><span class="checkbox {{ $hraWorkAtHeight->roof_fall_protection_system ? 'checked' : '' }}">{{ $hraWorkAtHeight->roof_fall_protection_system ? 'v' : '' }}</span> Penggunaan sistem perlindungan jatuh/ WaH PPE</div>
                    <div class="sub-row"><span class="checkbox {{ $hraWorkAtHeight->roof_communication_method ? 'checked' : '' }}">{{ $hraWorkAtHeight->roof_communication_method ? 'v' : '' }}</span> Metode komunikasi yang disepakati</div>
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
                <div class="row"><span class="checkbox {{ $hraWorkAtHeight->workers_have_training_proof ? 'checked' : '' }}">{{ $hraWorkAtHeight->workers_have_training_proof ? 'v' : '' }}</span> Apakah mereka yang terlibat memiliki bukti Pelatihan Bekerja di Ketinggian</div>
                <div class="row"><span class="checkbox {{ $hraWorkAtHeight->area_below_blocked ? 'checked' : '' }}">{{ $hraWorkAtHeight->area_below_blocked ? 'v' : '' }}</span> Apakah area di bawah tempat kerja telah diblokir</div>
                <div class="row"><span class="checkbox {{ $hraWorkAtHeight->workers_below_present ? 'checked' : '' }}">{{ $hraWorkAtHeight->workers_below_present ? 'v' : '' }}</span> Apakah ada yang bekerja di bawah mereka yang bekerja di ketinggian</div>
                <div class="row"><span class="checkbox {{ $hraWorkAtHeight->floor_suitable_for_access_equipment ? 'checked' : '' }}">{{ $hraWorkAtHeight->floor_suitable_for_access_equipment ? 'v' : '' }}</span> Apakah lantai/tanah cocok untuk digunakannya peralatan akses</div>
                <div class="row"><span class="checkbox {{ $hraWorkAtHeight->obstacles_near_work_location ? 'checked' : '' }}">{{ $hraWorkAtHeight->obstacles_near_work_location ? 'v' : '' }}</span> Apakah ada kendala di atau dekat lokasi kerja</div>
            </div>
            <div class="column">
                <div class="row"><span class="checkbox {{ $hraWorkAtHeight->ventilation_hazardous_emissions ? 'checked' : '' }}">{{ $hraWorkAtHeight->ventilation_hazardous_emissions ? 'v' : '' }}</span> Apakah ada ventilasi, cerobong asap yang dapat mengeluarkan media panas/berbau/berbahaya</div>
                <div class="row"><span class="checkbox {{ $hraWorkAtHeight->protection_needed_for_equipment ? 'checked' : '' }}">{{ $hraWorkAtHeight->protection_needed_for_equipment ? 'v' : '' }}</span> Apakah perlindungan dibutuhkan untuk peralatan akses WaH</div>
                <div class="row"><span class="checkbox {{ $hraWorkAtHeight->safe_access_exit_method ? 'checked' : '' }}">{{ $hraWorkAtHeight->safe_access_exit_method ? 'v' : '' }}</span> Apakah ada metode akses & keluar yang aman</div>
                <div class="row"><span class="checkbox {{ $hraWorkAtHeight->safe_material_handling_method ? 'checked' : '' }}">{{ $hraWorkAtHeight->safe_material_handling_method ? 'v' : '' }}</span> Apakah cara yang aman untuk menaik turunkan material dan peralatan telah ditentukan</div>
                <div class="row"><span class="checkbox {{ $hraWorkAtHeight->emergency_escape_plan_needed ? 'checked' : '' }}">{{ $hraWorkAtHeight->emergency_escape_plan_needed ? 'v' : '' }}</span> Apakah diperlukan rencana darurat & pelarian</div>
                <div class="row"><span class="checkbox {{ $hraWorkAtHeight->other_conditions_check ? 'checked' : '' }}">{{ $hraWorkAtHeight->other_conditions_check ? 'v' : '' }}</span> Lainnya</div>
            </div>
        </div>
        @if($hraWorkAtHeight->other_conditions_check && $hraWorkAtHeight->other_conditions_text)
        <div class="row" style="margin-top: 3px;"><span class="long-text-label">Keterangan kondisi lain:</span> {{ $hraWorkAtHeight->other_conditions_text }}</div>
        @endif
    </div>

    <!-- Environmental Conditions -->
    <div class="section">
        <div class="section-title">ENVIRONMENTAL CONDITIONS</div>
        <table>
            <tr>
                <td style="width: 25%; font-weight: bold; background-color: #f0f0f0;">Jarak pandang umum:</td>
                <td style="width: 25%;">{{ $hraWorkAtHeight->visibility_condition ?? 'N/A' }}</td>
                <td style="width: 25%; font-weight: bold; background-color: #f0f0f0;">Hujan:</td>
                <td style="width: 25%;">{{ $hraWorkAtHeight->rain_condition ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td style="font-weight: bold; background-color: #f0f0f0;">Kondisi permukaan tanah/lantai:</td>
                <td>{{ $hraWorkAtHeight->surface_condition ?? 'N/A' }}</td>
                <td style="font-weight: bold; background-color: #f0f0f0;">Angin:</td>
                <td>{{ $hraWorkAtHeight->wind_condition ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td style="font-weight: bold; background-color: #f0f0f0;">Permukaan licin dari tumpahan oli/bahan kimia:</td>
                <td>{{ $hraWorkAtHeight->chemical_spill_condition ?? 'N/A' }}</td>
                <td colspan="2"></td>
            </tr>
        </table>
        @if($hraWorkAtHeight->environment_other_conditions)
        <div class="row" style="margin-top: 3px;"><span class="long-text-label">Lainnya:</span> {{ $hraWorkAtHeight->environment_other_conditions }}</div>
        @endif
    </div>

    <!-- Additional Controls -->
    @if($hraWorkAtHeight->additional_controls)
    <div class="section">
        <div class="section-title">PENGENDALIAN TAMBAHAN</div>
        <div>{{ $hraWorkAtHeight->additional_controls }}</div>
    </div>
    @endif

    <!-- Main Permit & Approval -->
    <div class="section">
        <div class="section-title">MAIN PERMIT & APPROVAL</div>
        <div class="two-column">
            <div class="column">
                <div class="row"><span class="label">Permit Number:</span> {{ $permit->permit_number }}</div>
                <div class="row"><span class="label">Work Title:</span> {{ $permit->work_title }}</div>
                <div class="row"><span class="label">Status:</span> {{ strtoupper($permit->status) }}</div>
                <div class="row"><span class="label">Company:</span> {{ $permit->receiver_company_name ?? 'N/A' }}</div>
            </div>
            <div class="column">
                <div class="row"><span class="label">HRA Status:</span> {{ strtoupper($hraWorkAtHeight->approval_status ?? 'draft') }}</div>
                @if($hraWorkAtHeight->approval_status === 'approved')
                <div class="row"><span class="label">EHS Approved By:</span> {{ $hraWorkAtHeight->ehsApprover->name ?? 'N/A' }}</div>
                <div class="row"><span class="label">Approved At:</span> {{ $hraWorkAtHeight->ehs_approved_at ? $hraWorkAtHeight->ehs_approved_at->format('d/m/Y H:i') : 'N/A' }}</div>
                @endif
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div style="margin-top: 6px; text-align: center; font-size: 8px; color: #666;">
        Generated {{ date('d/m/Y H:i') }} | {{ $hraWorkAtHeight->hra_permit_number }}
    </div>
</body>
</html>
