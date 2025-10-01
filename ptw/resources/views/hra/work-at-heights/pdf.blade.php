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

                <!-- Mobile Elevation Platform -->
                <div class="assessment-item">
                    <div class="assessment-header">Mobile Elevation Platform</div>
                    <div class="row">
                        <span class="checkbox {{ $hraWorkAtHeight->mobile_elevation_checked ? 'checked' : '' }}">{{ $hraWorkAtHeight->mobile_elevation_checked ? '✓' : '' }}</span>
                        Mobile elevation platform
                    </div>
                    @if($hraWorkAtHeight->mobile_elevation_checked)
                    <div class="row" style="margin-left: 15px;">
                        <span class="checkbox {{ $hraWorkAtHeight->mobile_elevation_training_provided ? 'checked' : '' }}">{{ $hraWorkAtHeight->mobile_elevation_training_provided ? '✓' : '' }}</span>
                        Operator terlatih?
                    </div>
                    <div class="row" style="margin-left: 15px;">
                        <span class="checkbox {{ $hraWorkAtHeight->mobile_elevation_used_before ? 'checked' : '' }}">{{ $hraWorkAtHeight->mobile_elevation_used_before ? '✓' : '' }}</span>
                        Penggunaannya tertulus?
                    </div>
                    <div class="row" style="margin-left: 15px;">
                        <span class="checkbox {{ $hraWorkAtHeight->mobile_elevation_location_marked ? 'checked' : '' }}">{{ $hraWorkAtHeight->mobile_elevation_location_marked ? '✓' : '' }}</span>
                        Menggunakan Alat Pelindung Jatuh?
                    </div>
                    @endif
                </div>

                <!-- Fall Arrest -->
                <div class="assessment-item">
                    <div class="assessment-header">Fall Arrest</div>
                    <div class="row">
                        <span class="checkbox {{ $hraWorkAtHeight->fall_arrest_used ? 'checked' : '' }}">{{ $hraWorkAtHeight->fall_arrest_used ? '✓' : '' }}</span>
                        Fall arrest seperti FBH digunakan?
                    </div>
                    <div class="row">
                        <span class="checkbox {{ $hraWorkAtHeight->area_closed_from_below ? 'checked' : '' }}">{{ $hraWorkAtHeight->area_closed_from_below ? '✓' : '' }}</span>
                        Diperiksa sebelum digunakan
                    </div>
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
                        <span class="checkbox {{ $hraWorkAtHeight->roof_fragile_areas ? 'checked' : '' }}">{{ $hraWorkAtHeight->roof_fragile_areas ? '✓' : '' }}</span>
                        Apakah ada atap yang rawan?
                    </div>
                    <div class="row" style="margin-left: 15px;">
                        <span class="checkbox {{ $hraWorkAtHeight->roof_fall_protection ? 'checked' : '' }}">{{ $hraWorkAtHeight->roof_fall_protection ? '✓' : '' }}</span>
                        Pelindung jatuh/pelindung disisi tersedia?
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
                    <span class="checkbox {{ $hraWorkAtHeight->area_below_closed ? 'checked' : '' }}">{{ $hraWorkAtHeight->area_below_closed ? '✓' : '' }}</span>
                    Area di bawah pekerjaan berlangsung ditutup dari lalu lintas/pejalan kaki
                </div>
                <div class="row">
                    <span class="checkbox {{ $hraWorkAtHeight->ventilation_hazards ? 'checked' : '' }}">{{ $hraWorkAtHeight->ventilation_hazards ? '✓' : '' }}</span>
                    Ventilasi, cerobong, bukaan yang mengeluarkan udara/air yang panas/berbau/berbahaya
                </div>
                <div class="row">
                    <span class="checkbox {{ $hraWorkAtHeight->emergency_exit_available ? 'checked' : '' }}">{{ $hraWorkAtHeight->emergency_exit_available ? '✓' : '' }}</span>
                    Terdapat titik untuk keluar dalam kondisi darurat
                </div>
                <div class="row">
                    <span class="checkbox {{ $hraWorkAtHeight->safety_personnel_needed ? 'checked' : '' }}">{{ $hraWorkAtHeight->safety_personnel_needed ? '✓' : '' }}</span>
                    Personnel Safety atau Petugas lain yang diperlukan
                </div>
            </div>
            <div class="column">
                <div class="row">
                    <span class="checkbox {{ $hraWorkAtHeight->work_area_disturbances ? 'checked' : '' }}">{{ $hraWorkAtHeight->work_area_disturbances ? '✓' : '' }}</span>
                    Gangguan pada atau sekitar lokasi pekerjaan
                </div>
                <div class="row">
                    <span class="checkbox {{ $hraWorkAtHeight->equipment_protection ? 'checked' : '' }}">{{ $hraWorkAtHeight->equipment_protection ? '✓' : '' }}</span>
                    Bagian dari mesin/peralatan harus dilindungi
                </div>
                <div class="row">
                    <span class="checkbox {{ $hraWorkAtHeight->material_handling ? 'checked' : '' }}">{{ $hraWorkAtHeight->material_handling ? '✓' : '' }}</span>
                    Material/alat yang perlu dinaik/turunkan
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
