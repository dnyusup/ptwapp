<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>HRA Hot Work - {{ $hraHotWork->hra_permit_number }}</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            margin: 15px; 
            color: black;
            font-size: 12px;
        }
        .header { 
            text-align: center; 
            margin-bottom: 10px;
            border-bottom: 2px solid black;
            padding-bottom: 5px;
        }
        .title { 
            font-size: 16px; 
            font-weight: bold; 
        }
        .section { 
            margin-bottom: 6px; 
            border: 1px solid black;
            padding: 5px;
        }
        .section-title { 
            font-weight: bold; 
            font-size: 12px;
            margin-bottom: 5px;
            background-color: #f5f5f5;
            padding: 3px;
            margin: -5px -5px 5px -5px;
        }
        .row { 
            margin-bottom: 3px; 
            line-height: 1.2;
            font-size: 10px;
        }
        .label { 
            font-weight: bold; 
            display: inline-block;
            width: 140px;
            font-size: 10px;
        }
        .long-text-row {
            margin-bottom: 4px;
        }
        .long-text-label {
            font-weight: bold;
            display: block;
            font-size: 10px;
            margin-bottom: 2px;
        }
        .long-text-value {
            border: 1px solid #ccc;
            padding: 4px;
            background-color: #f9f9f9;
            min-height: 20px;
        }
        .checklist {
            margin-bottom: 8px;
        }
        .checklist-header {
            font-weight: bold;
            font-size: 12px;
            margin-bottom: 5px;
            background-color: #007bff;
            color: white;
            padding: 4px;
            text-align: center;
        }
        .checklist-item {
            display: table;
            width: 100%;
            margin-bottom: 2px;
            border-bottom: 1px solid #eee;
            padding: 2px 0;
        }
        .checklist-question {
            display: table-cell;
            width: 85%;
            font-size: 9px;
            padding-right: 5px;
            vertical-align: middle;
            line-height: 1.2;
        }
        .checklist-answer {
            display: table-cell;
            width: 15%;
            text-align: center;
            font-weight: bold;
            font-size: 10px;
            vertical-align: middle;
        }
        .yes { color: green; }
        .no { color: red; }
        .na { color: #666; }
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
        .fire-safety-grid {
            display: table;
            width: 100%;
            border-collapse: collapse;
        }
        .fire-safety-row {
            display: table-row;
        }
        .fire-safety-cell {
            display: table-cell;
            border: 1px solid #ccc;
            padding: 4px;
            text-align: center;
            width: 20%;
            font-size: 10px;
        }
        .fire-safety-header {
            background-color: #f0f0f0;
            font-weight: bold;
        }
        .sub-question {
            font-style: italic;
            color: #666;
            margin-left: 15px;
            font-size: 10px;
        }
        .approval-section {
            margin-top: 15px;
            border: 2px solid #28a745;
            background-color: #f8f9fa;
        }
        .approval-title {
            background-color: #28a745;
            color: white;
        }
        .page-break {
            page-break-before: always;
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
                        <div style="font-size: 5px; margin-top: 5px; word-wrap: break-word;">{{ $hraHotWork->hra_permit_number }}</div>
                    </div>
                @endif
            </div>
            <div style="display: table-cell; text-align: center; vertical-align: middle; padding-left: 20px;">
                <div class="title">HRA HOT WORK ASSESSMENT</div>
                <div style="font-size: 14px; margin-top: 5px;">{{ $hraHotWork->hra_permit_number }}</div>
                <div style="font-size: 10px; margin-top: 3px;">Main Permit: {{ $permit->permit_number }}</div>
            </div>
            <div style="display: table-cell; width: 100px; text-align: right; vertical-align: top; font-size: 10px;">
                <div style="font-weight: bold;">Generated:</div>
                <div>{{ date('d/m/Y H:i') }}</div>
                <div style="margin-top: 5px; font-weight: bold;">Status:</div>
                <div style="color: green;">{{ strtoupper($hraHotWork->approval_status) }}</div>
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
                    {{ $hraHotWork->worker_name ?? 'N/A' }}
                </div>
                <div class="row">
                    <span class="label">Phone Number:</span>
                    {{ $hraHotWork->worker_phone ?? 'N/A' }}
                </div>
                <div class="row">
                    <span class="label">Supervisor Name:</span>
                    {{ $hraHotWork->supervisor_name ?? 'N/A' }}
                </div>
                <div class="row">
                    <span class="label">Work Location:</span>
                    {{ $hraHotWork->work_location ?? 'N/A' }}
                </div>
            </div>
            <div class="column">
                <div class="row">
                    <span class="label">Start Date & Time:</span>
                    {{ $hraHotWork->start_datetime ? $hraHotWork->start_datetime->format('d/m/Y H:i') : 'N/A' }}
                </div>
                <div class="row">
                    <span class="label">End Date & Time:</span>
                    {{ $hraHotWork->end_datetime ? $hraHotWork->end_datetime->format('d/m/Y H:i') : 'N/A' }}
                </div>
                <div class="row">
                    <span class="label">Created By:</span>
                    {{ $hraHotWork->user->name ?? 'N/A' }}
                </div>
                <div class="row">
                    <span class="label">Main Permit:</span>
                    {{ $permit->permit_number }}
                </div>
            </div>
        </div>
        <div class="long-text-row">
            <div class="long-text-label">Work Description:</div>
            <div class="long-text-value">{{ $hraHotWork->work_description ?? 'N/A' }}</div>
        </div>
    </div>

    <!-- Hot Work Safety Checklist -->
    <div class="section">
        <div class="section-title">HRA HOT WORK ASSESSMENT</div>
        
        <!-- Section 1: Requirements within 11m/35ft from hot work -->
        <div style="font-weight: bold; font-size: 10px; margin: 4px 0; background-color: #e8f4f8; padding: 3px 5px; border-left: 3px solid #007bff;">
            Persyaratan dalam jarak 11m/35ft dari pekerjaan panas (termasuk di atas dan di bawah area kerja)
        </div>

        <div class="checklist-item">
            <div class="checklist-question">Semua bahan yang mudah terbakar disingkirkan atau dilindungi dengan penutup tahan api</div>
            <div class="checklist-answer {{ $hraHotWork->flammable_materials_removed === null ? 'na' : ($hraHotWork->flammable_materials_removed ? 'yes' : 'no') }}">
                {{ $hraHotWork->flammable_materials_removed === null ? '-' : ($hraHotWork->flammable_materials_removed ? 'V' : 'X') }}
            </div>
        </div>

        <div class="checklist-item">
            <div class="checklist-question">Cairan mudah terbakar, debu, serat, dan endapan minyak dihilangkan (debu di "dalam" dinding/atap/rongga)</div>
            <div class="checklist-answer {{ $hraHotWork->flammable_liquids_removed === null ? 'na' : ($hraHotWork->flammable_liquids_removed ? 'yes' : 'no') }}">
                {{ $hraHotWork->flammable_liquids_removed === null ? '-' : ($hraHotWork->flammable_liquids_removed ? 'V' : 'X') }}
            </div>
        </div>

        <div class="checklist-item">
            <div class="checklist-question">Lantai yang mudah terbakar dibasahi, ditutup dengan pasir basah atau penutup tahan api yang tumpang tindih</div>
            <div class="checklist-answer {{ $hraHotWork->flammable_floors_wetted === null ? 'na' : ($hraHotWork->flammable_floors_wetted ? 'yes' : 'no') }}">
                {{ $hraHotWork->flammable_floors_wetted === null ? '-' : ($hraHotWork->flammable_floors_wetted ? 'V' : 'X') }}
            </div>
        </div>

        <div class="checklist-item">
            <div class="checklist-question">Dinding/langit-langit/atap yang mudah terbakar dilindungi dengan penutup tahan api</div>
            <div class="checklist-answer {{ $hraHotWork->walls_ceiling_protected === null ? 'na' : ($hraHotWork->walls_ceiling_protected ? 'yes' : 'no') }}">
                {{ $hraHotWork->walls_ceiling_protected === null ? '-' : ($hraHotWork->walls_ceiling_protected ? 'V' : 'X') }}
            </div>
        </div>

        <div class="checklist-item">
            <div class="checklist-question">Lantai disapu bersih dari bahan yang mudah terbakar</div>
            <div class="checklist-answer {{ $hraHotWork->floors_swept_clean === null ? 'na' : ($hraHotWork->floors_swept_clean ? 'yes' : 'no') }}">
                {{ $hraHotWork->floors_swept_clean === null ? '-' : ($hraHotWork->floors_swept_clean ? 'V' : 'X') }}
            </div>
        </div>

        <div class="checklist-item">
            <div class="checklist-question">Material mudah terbakar di sisi lain dinding, langit-langit atau atap disingkirkan (perhatikan insulasinya)</div>
            <div class="checklist-answer {{ $hraHotWork->materials_other_side_removed === null ? 'na' : ($hraHotWork->materials_other_side_removed ? 'yes' : 'no') }}">
                {{ $hraHotWork->materials_other_side_removed === null ? '-' : ($hraHotWork->materials_other_side_removed ? 'V' : 'X') }}
            </div>
        </div>

        <div class="checklist-item">
            <div class="checklist-question">Atmosfer yang mudah meledak dihilangkan</div>
            <div class="checklist-answer {{ $hraHotWork->explosive_atmosphere_removed === null ? 'na' : ($hraHotWork->explosive_atmosphere_removed ? 'yes' : 'no') }}">
                {{ $hraHotWork->explosive_atmosphere_removed === null ? '-' : ($hraHotWork->explosive_atmosphere_removed ? 'V' : 'X') }}
            </div>
        </div>

        <div class="checklist-item">
            <div class="checklist-question">Semua bukaan dinding/lantai, termasuk saluran pembuangan, ditutup dengan penutup tahan api</div>
            <div class="checklist-answer {{ $hraHotWork->wall_floor_openings_covered === null ? 'na' : ($hraHotWork->wall_floor_openings_covered ? 'yes' : 'no') }}">
                {{ $hraHotWork->wall_floor_openings_covered === null ? '-' : ($hraHotWork->wall_floor_openings_covered ? 'V' : 'X') }}
            </div>
        </div>

        <div class="checklist-item">
            <div class="checklist-question">Saluran, konveyor, katup/saluran pembuangan yang terbuka secara otomatis, dll, terlindungi, terisolasi, atau keduanya</div>
            <div class="checklist-answer {{ $hraHotWork->ducts_conveyors_protected === null ? 'na' : ($hraHotWork->ducts_conveyors_protected ? 'yes' : 'no') }}">
                {{ $hraHotWork->ducts_conveyors_protected === null ? '-' : ($hraHotWork->ducts_conveyors_protected ? 'V' : 'X') }}
            </div>
        </div>

        <div class="checklist-item">
            <div class="checklist-question">Jika ada risiko kebakaran dari konduksi/radiasi, misalnya di sepanjang balok, tindakan pencegahan tambahan diterapkan</div>
            <div class="checklist-answer {{ $hraHotWork->fire_risk_prevention_applied === null ? 'na' : ($hraHotWork->fire_risk_prevention_applied ? 'yes' : 'no') }}">
                {{ $hraHotWork->fire_risk_prevention_applied === null ? '-' : ($hraHotWork->fire_risk_prevention_applied ? 'V' : 'X') }}
            </div>
        </div>

        <!-- Section 2: Requirements when working on enclosed equipment -->
        <div style="font-weight: bold; font-size: 10px; margin: 4px 0; background-color: #e8f4f8; padding: 3px 5px; border-left: 3px solid #007bff;">
            Persyaratan saat bekerja pada peralatan tertutup
        </div>

        <div class="checklist-item">
            <div class="checklist-question">Peralatan dibersihkan dari semua bahan yang mudah terbakar</div>
            <div class="checklist-answer {{ $hraHotWork->equipment_cleaned_flammable === null ? 'na' : ($hraHotWork->equipment_cleaned_flammable ? 'yes' : 'no') }}">
                {{ $hraHotWork->equipment_cleaned_flammable === null ? '-' : ($hraHotWork->equipment_cleaned_flammable ? 'V' : 'X') }}
            </div>
        </div>

        <div class="checklist-item">
            <div class="checklist-question">Wadah dikosongkan, dibersihkan, dan diuji bebas dari cairan dan uap yang mudah terbakar (Lengkapi form-H)</div>
            <div class="checklist-answer {{ $hraHotWork->containers_emptied_cleaned === null ? 'na' : ($hraHotWork->containers_emptied_cleaned ? 'yes' : 'no') }}">
                {{ $hraHotWork->containers_emptied_cleaned === null ? '-' : ($hraHotWork->containers_emptied_cleaned ? 'V' : 'X') }}
            </div>
        </div>

        <!-- Section 3: Building panels/materials -->
        <div style="font-weight: bold; font-size: 10px; margin: 4px 0; background-color: #e8f4f8; padding: 3px 5px; border-left: 3px solid #007bff;">
            Panel bangunan/material
        </div>

        <div class="checklist-item">
            <div class="checklist-question">Panel bangunan/material yang sedang dikerjakan adalah diketahui tidak mudah terbakar</div>
            <div class="checklist-answer {{ $hraHotWork->building_materials_non_flammable === null ? 'na' : ($hraHotWork->building_materials_non_flammable ? 'yes' : 'no') }}">
                {{ $hraHotWork->building_materials_non_flammable === null ? '-' : ($hraHotWork->building_materials_non_flammable ? 'V' : 'X') }}
            </div>
        </div>

        <div class="checklist-item">
            <div class="checklist-question">Jika "tidak" pada pertanyaan di atas, bahan yang mudah terbakar HARUS dipotong hingga minimal 50 cm dan dilindungi oleh bahan pelindung yang tidak mudah terbakar</div>
            <div class="checklist-answer {{ $hraHotWork->flammable_materials_cut_protected === null ? 'na' : ($hraHotWork->flammable_materials_cut_protected ? 'yes' : 'no') }}">
                {{ $hraHotWork->flammable_materials_cut_protected === null ? '-' : ($hraHotWork->flammable_materials_cut_protected ? 'V' : 'X') }}
            </div>
        </div>

        <div style="display: table; width: 100%; font-weight: bold; font-size: 10px; margin: 4px 0; background-color: #e8f4f8; padding: 3px 5px; border-left: 3px solid #007bff;">
            <div style="display: table-cell; width: 85%; vertical-align: middle;">Ventilasi yang cukup di tempat kerja @if($hraHotWork->ventilation_type)({{ $hraHotWork->ventilation_type }})@endif</div>
            <div style="display: table-cell; width: 15%; text-align: center; vertical-align: middle;" class="{{ $hraHotWork->ventilation_adequate === null ? 'na' : ($hraHotWork->ventilation_adequate ? 'yes' : 'no') }}">{{ $hraHotWork->ventilation_adequate === null ? '-' : ($hraHotWork->ventilation_adequate ? 'V' : 'X') }}</div>
        </div>

        <div style="display: table; width: 100%; font-weight: bold; font-size: 10px; margin: 4px 0; background-color: #e8f4f8; padding: 3px 5px; border-left: 3px solid #007bff;">
            <div style="display: table-cell; width: 85%; vertical-align: middle;">Lampu tiup dan tabung gas hanya boleh dipasang atau diganti di area terbuka dan berventilasi baik</div>
            <div style="display: table-cell; width: 15%; text-align: center; vertical-align: middle;" class="{{ $hraHotWork->gas_lamps_open_area === null ? 'na' : ($hraHotWork->gas_lamps_open_area ? 'yes' : 'no') }}">{{ $hraHotWork->gas_lamps_open_area === null ? '-' : ($hraHotWork->gas_lamps_open_area ? 'V' : 'X') }}</div>
        </div>

        <div style="display: table; width: 100%; font-weight: bold; font-size: 10px; margin: 4px 0; background-color: #e8f4f8; padding: 3px 5px; border-left: 3px solid #007bff;">
            <div style="display: table-cell; width: 85%; vertical-align: middle;">Apakah semua peralatan telah dipasang dan pengalasan dimonitor dari pengelasan dalam kondisi baik?</div>
            <div style="display: table-cell; width: 15%; text-align: center; vertical-align: middle;" class="{{ $hraHotWork->equipment_installed_monitored === null ? 'na' : ($hraHotWork->equipment_installed_monitored ? 'yes' : 'no') }}">{{ $hraHotWork->equipment_installed_monitored === null ? '-' : ($hraHotWork->equipment_installed_monitored ? 'V' : 'X') }}</div>
        </div>

        <div style="display: table; width: 100%; font-weight: bold; font-size: 10px; margin: 4px 0; background-color: #e8f4f8; padding: 3px 5px; border-left: 3px solid #007bff;">
            <div style="display: table-cell; width: 85%; vertical-align: middle;">Semua pekerja yang ada di area tersebut diberitahu tentang pekerjaan panas yang sedang dilakukan</div>
            <div style="display: table-cell; width: 15%; text-align: center; vertical-align: middle;" class="{{ $hraHotWork->workers_notified === null ? 'na' : ($hraHotWork->workers_notified ? 'yes' : 'no') }}">{{ $hraHotWork->workers_notified === null ? '-' : ($hraHotWork->workers_notified ? 'V' : 'X') }}</div>
        </div>
    </div>

    <!-- Footer for page 1 -->
    <div style="text-align: center; margin-top: 10px; font-size: 9px; color: #666;">
        Generated on {{ date('d/m/Y H:i:s') }} | HRA Hot Work Assessment | Page 1 of 2
    </div>

    <!-- Fire Safety Equipment -->
    <div class="page-break">
        <!-- Mini header for new page -->
        <div style="text-align: center; margin-bottom: 15px; border-bottom: 1px solid #ccc; padding-bottom: 8px;">
            <div style="font-size: 16px; font-weight: bold;">HRA HOT WORK ASSESSMENT</div>
            <div style="font-size: 12px; margin-top: 3px;">{{ $hraHotWork->hra_permit_number }} - Page 2</div>
        </div>
    </div>
    
    <div class="section">
        <div class="section-title">PERALATAN PEMADAM API</div>
        
        <div style="margin-bottom: 5px;">
            <strong style="font-size: 10px;">APAR:</strong>
        </div>
        <div class="fire-safety-grid">
            <div class="fire-safety-row">
                <div class="fire-safety-cell fire-safety-header">Air</div>
                <div class="fire-safety-cell fire-safety-header">Powder</div>
                <div class="fire-safety-cell fire-safety-header">CO2</div>
                <div class="fire-safety-cell fire-safety-header">Foam</div>
                <div class="fire-safety-cell fire-safety-header">Fire Blanket</div>
            </div>
            <div class="fire-safety-row">
                <div class="fire-safety-cell {{ $hraHotWork->apar_air ? 'yes' : 'no' }}">
                    {{ $hraHotWork->apar_air ? 'YA' : 'TIDAK' }}
                </div>
                <div class="fire-safety-cell {{ $hraHotWork->apar_powder ? 'yes' : 'no' }}">
                    {{ $hraHotWork->apar_powder ? 'YA' : 'TIDAK' }}
                </div>
                <div class="fire-safety-cell {{ $hraHotWork->apar_co2 ? 'yes' : 'no' }}">
                    {{ $hraHotWork->apar_co2 ? 'YA' : 'TIDAK' }}
                </div>
                <div class="fire-safety-cell {{ $hraHotWork->apar_foam ? 'yes' : 'no' }}">
                    {{ $hraHotWork->apar_foam ? 'YA' : 'TIDAK' }}
                </div>
                <div class="fire-safety-cell {{ $hraHotWork->fire_blanket ? 'yes' : 'no' }}">
                    {{ $hraHotWork->fire_blanket ? 'YA' : 'TIDAK' }}
                </div>
            </div>
        </div>

        <!-- Fire Watch Section -->
        <div style="margin-top: 8px; border-top: 1px solid #ccc; padding-top: 8px;">
            <div style="font-weight: bold; font-size: 10px; margin-bottom: 5px; background-color: #dc3545; color: white; padding: 3px 5px;">
                FIRE WATCH
            </div>
            
            <div class="checklist-item">
                <div class="checklist-question">Petugas Fire Watch (harus dilatih dalam bahaya, risiko, dan pengendalian kebakaran. Pelatihan terverifikasi?)</div>
                <div class="checklist-answer {{ $hraHotWork->fire_watch_officer === null ? 'na' : ($hraHotWork->fire_watch_officer ? 'yes' : 'no') }}">
                    {{ $hraHotWork->fire_watch_officer === null ? '-' : ($hraHotWork->fire_watch_officer ? 'V' : 'X') }}
                </div>
            </div>

            @if($hraHotWork->fire_watch_officer && $hraHotWork->fire_watch_name)
            <div style="margin: 5px 0; padding-left: 15px; font-size: 9px;">
                <span class="label">Nama Petugas Fire Watch:</span>
                {{ $hraHotWork->fire_watch_name }}
            </div>
            @endif
        </div>

        <!-- Monitoring Section -->
        <div style="margin-top: 8px;">
            <div style="font-weight: bold; font-size: 10px; margin-bottom: 5px;">Membutuhkan monitoring:</div>
            
            <div class="checklist-item">
                <div class="checklist-question">Bangunan dilindungi oleh alat penyiram otomatis</div>
                <div class="checklist-answer {{ $hraHotWork->monitoring_sprinkler === null ? 'na' : ($hraHotWork->monitoring_sprinkler ? 'yes' : 'no') }}">
                    {{ $hraHotWork->monitoring_sprinkler === null ? '-' : ($hraHotWork->monitoring_sprinkler ? 'V' : 'X') }}
                </div>
            </div>

            <div class="checklist-item">
                <div class="checklist-question">Tidak ada bahan yang mudah terbakar yang digunakan pada konstruksi atap/langit-langit, dinding atau lantai (Tidak memberikan penilaian jika TIDAK YAKIN)</div>
                <div class="checklist-answer {{ $hraHotWork->monitoring_combustible === null ? 'na' : ($hraHotWork->monitoring_combustible ? 'yes' : 'no') }}">
                    {{ $hraHotWork->monitoring_combustible === null ? '-' : ($hraHotWork->monitoring_combustible ? 'V' : 'X') }}
                </div>
            </div>

            <div class="checklist-item">
                <div class="checklist-question">Semua bahan yang mudah terbakar/debu, serat atau endapan berminyak, berada setidaknya 11m dari area kerja</div>
                <div class="checklist-answer {{ $hraHotWork->monitoring_distance === null ? 'na' : ($hraHotWork->monitoring_distance ? 'yes' : 'no') }}">
                    {{ $hraHotWork->monitoring_distance === null ? '-' : ($hraHotWork->monitoring_distance ? 'V' : 'X') }}
                </div>
            </div>

            <div class="checklist-item">
                <div class="checklist-question">Berapa lama inspeksi tambahan diperlukan?</div>
                <div class="checklist-answer">
                    {{ $hraHotWork->additional_inspection_duration ?? '-' }}
                </div>
            </div>

            <!-- Fire Watch Time Form -->
            <table style="width: 100%; border-collapse: collapse; margin-top: 8px; font-size: 9px;">
                <tr>
                    <td style="border: 1px solid #000; padding: 4px; width: 50%;">
                        <strong>Waktu Mulai :</strong>
                    </td>
                    <td style="border: 1px solid #000; padding: 4px; width: 50%;">
                        <strong>Waktu Penyelesaian :</strong>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style="border: 1px solid #000; padding: 4px; text-align: center;">
                        Waktu Penyelesaian <span style="color: red; font-weight: bold;">HARUS</span> setidaknya 1 jam setelah selesainya pekerjaan panas)
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style="border: 1px solid #000; padding: 0;">
                        <table style="width: 100%; border-collapse: collapse;">
                            <tr>
                                <td style="border-right: 1px solid #000; padding: 4px; width: 20%;"><strong>Waktu Fire Inspection:</strong></td>
                                <td style="border-right: 1px solid #000; padding: 4px; width: 20%;">&nbsp;</td>
                                <td style="border-right: 1px solid #000; padding: 4px; width: 20%;">&nbsp;</td>
                                <td style="border-right: 1px solid #000; padding: 4px; width: 20%;">&nbsp;</td>
                                <td style="padding: 4px; width: 20%;">&nbsp;</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <!-- Emergency Systems Section -->
    <div class="section">
        <div class="section-title">EMERGENCY SYSTEMS</div>
        
        <div class="row" style="white-space: nowrap;">
            <span class="label" style="width: auto;">Titik panggilan darurat/alarm kebakaran terdekat:</span>
            {{ $hraHotWork->emergency_call_point ?? '-' }}
        </div>

        <div class="checklist-item">
            <div class="checklist-question">Apakah sistem penyiram otomatis (jika ada) tidak berfungsi?</div>
            <div class="checklist-answer {{ $hraHotWork->sprinkler_system_disabled === null ? 'na' : ($hraHotWork->sprinkler_system_disabled ? 'yes' : 'no') }}">
                {{ $hraHotWork->sprinkler_system_disabled === null ? '-' : ($hraHotWork->sprinkler_system_disabled ? 'V' : 'X') }}
            </div>
        </div>

        <div class="checklist-item">
            <div class="checklist-question">Apakah sistem deteksi kebakaran (jika berlaku) harus diisolasi >10 jam?</div>
            <div class="checklist-answer {{ $hraHotWork->fire_detection_isolated === null ? 'na' : ($hraHotWork->fire_detection_isolated ? 'yes' : 'no') }}">
                {{ $hraHotWork->fire_detection_isolated === null ? '-' : ($hraHotWork->fire_detection_isolated ? 'V' : 'X') }}
            </div>
        </div>

        @if($hraHotWork->fire_detection_isolated)
        <div style="margin-top: 5px; padding: 5px; background-color: #fff3cd; border-left: 3px solid #ffc107; font-size: 9px;">
            <div style="font-weight: bold; margin-bottom: 4px;">Insurers Notification:</div>
            <div style="display: table; width: 100%;">
                <div style="display: table-cell; width: 50%; vertical-align: top;">
                    <div class="row">
                        <span class="label">Isolation notified by:</span>
                        {{ $hraHotWork->isolation_notified_by ?? '-' }}
                    </div>
                    <div class="row">
                        <span class="label">Isolation notified when:</span>
                        {{ $hraHotWork->isolation_notified_when ?? '-' }}
                    </div>
                </div>
                <div style="display: table-cell; width: 50%; vertical-align: top;">
                    <div class="row">
                        <span class="label">Reinstatement notified by:</span>
                        {{ $hraHotWork->reinstatement_notified_by ?? '-' }}
                    </div>
                    <div class="row">
                        <span class="label">Reinstatement notified when:</span>
                        {{ $hraHotWork->reinstatement_notified_when ?? '-' }}
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Approval Section -->
    @if($hraHotWork->approval_status === 'approved')
    <div class="section approval-section">
        <div class="section-title approval-title">APPROVAL STATUS</div>
        <div class="two-column">
            <div class="column">
                <div class="row">
                    <span class="label">Location Owner:</span>
                    {{ $permit->locationOwner->name ?? 'N/A' }}
                </div>
                <div class="row">
                    <span class="label">Approval Status:</span>
                    <span style="color: green; font-weight: bold;">{{ strtoupper($hraHotWork->area_owner_approval) }}</span>
                </div>
                <div class="row">
                    <span class="label">Approved By:</span>
                    {{ $hraHotWork->areaOwnerApprovedBy->name ?? 'N/A' }}
                </div>
                <div class="row">
                    <span class="label">Approved At:</span>
                    {{ $hraHotWork->area_owner_approved_at ? $hraHotWork->area_owner_approved_at->format('d/m/Y H:i') : 'N/A' }}
                </div>
            </div>
            <div class="column">
                <div class="row">
                    <span class="label">EHS Team:</span>
                    EHS Department
                </div>
                <div class="row">
                    <span class="label">Approval Status:</span>
                    <span style="color: green; font-weight: bold;">{{ strtoupper($hraHotWork->ehs_approval) }}</span>
                </div>
                <div class="row">
                    <span class="label">Approved By:</span>
                    {{ $hraHotWork->ehsApprovedBy->name ?? 'N/A' }}
                </div>
                <div class="row">
                    <span class="label">Approved At:</span>
                    {{ $hraHotWork->ehs_approved_at ? $hraHotWork->ehs_approved_at->format('d/m/Y H:i') : 'N/A' }}
                </div>
            </div>
        </div>
        <div style="margin-top: 10px; text-align: center; font-weight: bold; color: green;">
            FINAL APPROVAL: {{ $hraHotWork->final_approved_at ? $hraHotWork->final_approved_at->format('d/m/Y H:i') : 'N/A' }}
        </div>
    </div>
    @endif

    <!-- Main Permit Information -->
    <div class="section">
        <div class="section-title">MAIN PERMIT INFORMATION</div>
        <div style="display: table; width: 100%; margin-bottom: 10px;">
            <div style="display: table-cell; width: 75%; vertical-align: top; padding-right: 15px;">
                <div class="two-column">
                    <div class="column" style="padding-right: 15px;">
                        <div class="row">
                            <span class="label" style="width: 120px;">Permit Number:</span>
                            <span style="word-wrap: break-word; word-break: break-all; max-width: 150px; display: inline-block; vertical-align: top;">{{ $permit->permit_number }}</span>
                        </div>
                        <div class="row">
                            <span class="label" style="width: 120px;">Work Title:</span>
                            <span style="word-wrap: break-word; max-width: 150px; display: inline-block; vertical-align: top;">{{ $permit->work_title }}</span>
                        </div>
                        <div class="row">
                            <span class="label" style="width: 120px;">Department:</span>
                            {{ $permit->department }}
                        </div>
                        <div class="row">
                            <span class="label" style="width: 120px;">Location:</span>
                            <span style="word-wrap: break-word; max-width: 150px; display: inline-block; vertical-align: top;">{{ $permit->work_location }}</span>
                        </div>
                    </div>
                    <div class="column">
                        <div class="row">
                            <span class="label" style="width: 120px;">Permit Issuer:</span>
                            <span style="word-wrap: break-word; max-width: 150px; display: inline-block; vertical-align: top;">{{ $permit->permitIssuer->name ?? 'N/A' }}</span>
                        </div>
                        <div class="row">
                            <span class="label" style="width: 120px;">Authorizer:</span>
                            <span style="word-wrap: break-word; max-width: 150px; display: inline-block; vertical-align: top;">{{ $permit->authorizer->name ?? 'N/A' }}</span>
                        </div>
                        <div class="row">
                            <span class="label" style="width: 120px;">Receiver:</span>
                            <span style="word-wrap: break-word; max-width: 150px; display: inline-block; vertical-align: top;">{{ $permit->receiver->name ?? 'N/A' }}</span>
                        </div>
                        <div class="row">
                            <span class="label" style="width: 120px;">Valid Period:</span>
                            <span style="white-space: nowrap;">{{ $permit->start_date->format('d/m/Y') }} - {{ $permit->end_date->format('d/m/Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div style="display: table-cell; width: 25%; text-align: center; vertical-align: middle; border-left: 1px solid #ccc; padding-left: 15px;">
                @if(isset($permitQrCode))
                    <div style="font-weight: bold; font-size: 10px; margin-bottom: 8px; color: #666;">Main Permit QR</div>
                    <img src="data:image/svg+xml;base64,{{ $permitQrCode }}" alt="Main Permit QR Code" style="width: 80px; height: 80px; border: 1px solid #ddd;">
                @else
                    <div style="width: 80px; height: 80px; border: 2px solid #666; margin: 0 auto; text-align: center; font-size: 7px; padding: 5px; background: #f9f9f9;">
                        <div style="font-weight: bold; margin-bottom: 3px;">MAIN PERMIT</div>
                        <div style="font-size: 6px; word-wrap: break-word;">{{ $permit->permit_number }}</div>
                    </div>
                @endif
                                <div style="font-size: 8px; color: #666; margin-top: 5px;">Scan untuk detail permit utama</div>
            </div>
        </div>
    </div>

    <!-- Footer for page 2 -->
    <div style="text-align: center; margin-top: 20px; font-size: 10px; color: #666;">
        Generated on {{ date('d/m/Y H:i:s') }} | HRA Hot Work Assessment | Page 2 of 2
    </div>
</body>
</html>
            </div>
        </div>
    </div>


</body>
</html>