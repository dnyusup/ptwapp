<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>HRA LOTO/Isolation - {{ $hraLotoIsolation->hra_permit_number }}</title>
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
            border: 1px solid #ccc;
            padding: 4px;
            background-color: #f9f9f9;
            min-height: 20px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
        }
        .table th, .table td {
            border: 1px solid black;
            padding: 4px;
            text-align: center;
            font-size: 11px;
        }
        .table th {
            background-color: #f5f5f5;
            font-weight: bold;
        }
        .table td.text-left {
            text-align: left;
            font-weight: bold;
        }
        .checkbox {
            width: 12px;
            height: 12px;
            border: 1px solid black;
            display: inline-block;
            text-align: center;
            line-height: 10px;
            font-size: 10px;
            margin-right: 5px;
            background-color: white;
        }
        .checkbox.checked {
            background-color: black;
            color: white;
        }
        .qr-section {
            text-align: center;
            margin-top: 15px;
            padding: 8px;
            border: 1px solid black;
        }
        .qr-container {
            display: inline-block;
            margin: 0 10px;
            vertical-align: top;
        }
        .qr-label {
            font-weight: bold;
            margin-bottom: 5px;
        }
        .utility-table {
            font-size: 10px;
        }
        .utility-table th, .utility-table td {
            padding: 2px;
        }
        .two-column {
            width: 48%;
            display: inline-block;
            vertical-align: top;
        }
        .two-column:nth-child(2) {
            margin-left: 4%;
        }
        .checklist-item {
            margin-bottom: 4px;
            font-size: 11px;
        }
        .badge-list {
            margin-top: 4px;
        }
        .badge {
            display: inline-block;
            padding: 2px 6px;
            border: 1px solid #333;
            margin-right: 4px;
            font-size: 10px;
            background-color: #e9ecef;
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
                        <div style="font-size: 5px; margin-top: 5px; word-wrap: break-word;">{{ $hraLotoIsolation->hra_permit_number }}</div>
                    </div>
                @endif
            </div>
            <div style="display: table-cell; text-align: center; vertical-align: middle; padding-left: 20px;">
                <div class="title">HRA LOTO/ISOLATION ASSESSMENT</div>
                <div style="font-size: 14px; margin-top: 5px;">{{ $hraLotoIsolation->hra_permit_number }}</div>
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
                    {{ $hraLotoIsolation->worker_name ?? 'N/A' }}
                </div>
                <div class="row">
                    <span class="label">Phone Number:</span>
                    {{ $hraLotoIsolation->worker_phone ?? 'N/A' }}
                </div>
                <div class="row">
                    <span class="label">Supervisor Name:</span>
                    {{ $hraLotoIsolation->supervisor_name ?? 'N/A' }}
                </div>
            </div>
            <div class="column">
                <div class="row">
                    <span class="label">Work Location:</span>
                    {{ $hraLotoIsolation->work_location ?? 'N/A' }}
                </div>
                <div class="row">
                    <span class="label">Start Time:</span>
                    {{ \Carbon\Carbon::parse($hraLotoIsolation->start_datetime)->format('d/m/Y H:i') }}
                </div>
                <div class="row">
                    <span class="label">End Time:</span>
                    {{ \Carbon\Carbon::parse($hraLotoIsolation->end_datetime)->format('d/m/Y H:i') }}
                </div>
            </div>
        </div>
        <div class="long-text-row">
            <div class="long-text-label">Work Description:</div>
            <div class="long-text-content">{{ $hraLotoIsolation->work_description }}</div>
        </div>
    </div>

    <!-- Isolasi Mesin/Tangki -->
    <div class="section">
        <div class="section-title">ISOLASI MESIN/TANGKI</div>
        <div class="long-text-row">
            <div class="long-text-label">Machine/Tank Name:</div>
            <div class="long-text-content">{{ $hraLotoIsolation->machine_tank_name ?? '-' }}</div>
        </div>
        
        <table class="table">
            <thead>
                <tr>
                    <th style="width: 25%;">Jenis Energi</th>
                    <th style="width: 18.75%;">Mati/Off</th>
                    <th style="width: 18.75%;">Dikunci</th>
                    <th style="width: 18.75%;">Diperiksa</th>
                    <th style="width: 18.75%;">Dipasang Tag</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="text-left">Panel Listrik</td>
                    <td><span class="checkbox {{ $hraLotoIsolation->panel_listrik_mati ? 'checked' : '' }}">{{ $hraLotoIsolation->panel_listrik_mati ? '✓' : '' }}</span></td>
                    <td><span class="checkbox {{ $hraLotoIsolation->panel_listrik_dikunci ? 'checked' : '' }}">{{ $hraLotoIsolation->panel_listrik_dikunci ? '✓' : '' }}</span></td>
                    <td><span class="checkbox {{ $hraLotoIsolation->panel_listrik_diperiksa ? 'checked' : '' }}">{{ $hraLotoIsolation->panel_listrik_diperiksa ? '✓' : '' }}</span></td>
                    <td><span class="checkbox {{ $hraLotoIsolation->panel_listrik_dipasang_tag ? 'checked' : '' }}">{{ $hraLotoIsolation->panel_listrik_dipasang_tag ? '✓' : '' }}</span></td>
                </tr>
                <tr>
                    <td class="text-left">Pneumatic</td>
                    <td><span class="checkbox {{ $hraLotoIsolation->pneumatic_mati ? 'checked' : '' }}">{{ $hraLotoIsolation->pneumatic_mati ? '✓' : '' }}</span></td>
                    <td><span class="checkbox {{ $hraLotoIsolation->pneumatic_dikunci ? 'checked' : '' }}">{{ $hraLotoIsolation->pneumatic_dikunci ? '✓' : '' }}</span></td>
                    <td><span class="checkbox {{ $hraLotoIsolation->pneumatic_diperiksa ? 'checked' : '' }}">{{ $hraLotoIsolation->pneumatic_diperiksa ? '✓' : '' }}</span></td>
                    <td><span class="checkbox {{ $hraLotoIsolation->pneumatic_dipasang_tag ? 'checked' : '' }}">{{ $hraLotoIsolation->pneumatic_dipasang_tag ? '✓' : '' }}</span></td>
                </tr>
                <tr>
                    <td class="text-left">Hydraulic</td>
                    <td><span class="checkbox {{ $hraLotoIsolation->hydraulic_mati ? 'checked' : '' }}">{{ $hraLotoIsolation->hydraulic_mati ? '✓' : '' }}</span></td>
                    <td><span class="checkbox {{ $hraLotoIsolation->hydraulic_dikunci ? 'checked' : '' }}">{{ $hraLotoIsolation->hydraulic_dikunci ? '✓' : '' }}</span></td>
                    <td><span class="checkbox {{ $hraLotoIsolation->hydraulic_diperiksa ? 'checked' : '' }}">{{ $hraLotoIsolation->hydraulic_diperiksa ? '✓' : '' }}</span></td>
                    <td><span class="checkbox {{ $hraLotoIsolation->hydraulic_dipasang_tag ? 'checked' : '' }}">{{ $hraLotoIsolation->hydraulic_dipasang_tag ? '✓' : '' }}</span></td>
                </tr>
                <tr>
                    <td class="text-left">Gravitasi</td>
                    <td><span class="checkbox {{ $hraLotoIsolation->gravitasi_mati ? 'checked' : '' }}">{{ $hraLotoIsolation->gravitasi_mati ? '✓' : '' }}</span></td>
                    <td><span class="checkbox {{ $hraLotoIsolation->gravitasi_dikunci ? 'checked' : '' }}">{{ $hraLotoIsolation->gravitasi_dikunci ? '✓' : '' }}</span></td>
                    <td><span class="checkbox {{ $hraLotoIsolation->gravitasi_diperiksa ? 'checked' : '' }}">{{ $hraLotoIsolation->gravitasi_diperiksa ? '✓' : '' }}</span></td>
                    <td><span class="checkbox {{ $hraLotoIsolation->gravitasi_dipasang_tag ? 'checked' : '' }}">{{ $hraLotoIsolation->gravitasi_dipasang_tag ? '✓' : '' }}</span></td>
                </tr>
                <tr>
                    <td class="text-left">Spring/Per</td>
                    <td><span class="checkbox {{ $hraLotoIsolation->spring_per_mati ? 'checked' : '' }}">{{ $hraLotoIsolation->spring_per_mati ? '✓' : '' }}</span></td>
                    <td><span class="checkbox {{ $hraLotoIsolation->spring_per_dikunci ? 'checked' : '' }}">{{ $hraLotoIsolation->spring_per_dikunci ? '✓' : '' }}</span></td>
                    <td><span class="checkbox {{ $hraLotoIsolation->spring_per_diperiksa ? 'checked' : '' }}">{{ $hraLotoIsolation->spring_per_diperiksa ? '✓' : '' }}</span></td>
                    <td><span class="checkbox {{ $hraLotoIsolation->spring_per_dipasang_tag ? 'checked' : '' }}">{{ $hraLotoIsolation->spring_per_dipasang_tag ? '✓' : '' }}</span></td>
                </tr>
                <tr>
                    <td class="text-left">Rotasi/Gerakan</td>
                    <td><span class="checkbox {{ $hraLotoIsolation->rotasi_gerakan_mati ? 'checked' : '' }}">{{ $hraLotoIsolation->rotasi_gerakan_mati ? '✓' : '' }}</span></td>
                    <td><span class="checkbox {{ $hraLotoIsolation->rotasi_gerakan_dikunci ? 'checked' : '' }}">{{ $hraLotoIsolation->rotasi_gerakan_dikunci ? '✓' : '' }}</span></td>
                    <td><span class="checkbox {{ $hraLotoIsolation->rotasi_gerakan_diperiksa ? 'checked' : '' }}">{{ $hraLotoIsolation->rotasi_gerakan_diperiksa ? '✓' : '' }}</span></td>
                    <td><span class="checkbox {{ $hraLotoIsolation->rotasi_gerakan_dipasang_tag ? 'checked' : '' }}">{{ $hraLotoIsolation->rotasi_gerakan_dipasang_tag ? '✓' : '' }}</span></td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Isolasi Listrik -->
    <div class="section">
        <div class="section-title">ISOLASI LISTRIK</div>
        <div class="two-column">
            <div class="column">
                <div style="font-weight: bold; margin-bottom: 6px; text-decoration: underline;">Panel Listrik:</div>
                <div class="checklist-item">
                    <span class="checkbox {{ $hraLotoIsolation->bekerja_panel_listrik == 'ya' ? 'checked' : '' }}">{{ $hraLotoIsolation->bekerja_panel_listrik == 'ya' ? '✓' : '' }}</span>
                    Bekerja Pada Panel Listrik
                </div>
                <div class="checklist-item">
                    <span class="checkbox {{ $hraLotoIsolation->referensi_manual_panel == 'ya' ? 'checked' : '' }}">{{ $hraLotoIsolation->referensi_manual_panel == 'ya' ? '✓' : '' }}</span>
                    Referensi Manual
                </div>
                <div class="checklist-item">
                    <span class="checkbox {{ $hraLotoIsolation->saklar_diposisi_off == 'ya' ? 'checked' : '' }}">{{ $hraLotoIsolation->saklar_diposisi_off == 'ya' ? '✓' : '' }}</span>
                    Saklar di Posisi OFF
                </div>
                <div class="checklist-item">
                    <span class="checkbox {{ $hraLotoIsolation->tag_dipasang_panel == 'ya' ? 'checked' : '' }}">{{ $hraLotoIsolation->tag_dipasang_panel == 'ya' ? '✓' : '' }}</span>
                    Tag Dipasang
                </div>
                <div class="checklist-item">
                    <span class="checkbox {{ $hraLotoIsolation->sekring_cb_dimatikan == 'ya' ? 'checked' : '' }}">{{ $hraLotoIsolation->sekring_cb_dimatikan == 'ya' ? '✓' : '' }}</span>
                    Sekring/CB Dimatikan
                </div>
                <div class="checklist-item">
                    <span class="checkbox {{ $hraLotoIsolation->panel_off_panel == 'ya' ? 'checked' : '' }}">{{ $hraLotoIsolation->panel_off_panel == 'ya' ? '✓' : '' }}</span>
                    Panel OFF
                </div>
            </div>
            <div class="column">
                <div style="font-weight: bold; margin-bottom: 6px; text-decoration: underline;">Sistem Mekanis:</div>
                <div class="checklist-item">
                    <span class="checkbox {{ $hraLotoIsolation->bekerja_sistem_mekanis == 'ya' ? 'checked' : '' }}">{{ $hraLotoIsolation->bekerja_sistem_mekanis == 'ya' ? '✓' : '' }}</span>
                    Bekerja Pada Sistem Mekanis
                </div>
                <div class="checklist-item">
                    <span class="checkbox {{ $hraLotoIsolation->referensi_manual_sistem == 'ya' ? 'checked' : '' }}">{{ $hraLotoIsolation->referensi_manual_sistem == 'ya' ? '✓' : '' }}</span>
                    Referensi Manual
                </div>
                <div class="checklist-item">
                    <span class="checkbox {{ $hraLotoIsolation->safety_switch_off == 'ya' ? 'checked' : '' }}">{{ $hraLotoIsolation->safety_switch_off == 'ya' ? '✓' : '' }}</span>
                    Safety Switch OFF
                </div>
                <div class="checklist-item">
                    <span class="checkbox {{ $hraLotoIsolation->tag_dipasang_sistem == 'ya' ? 'checked' : '' }}">{{ $hraLotoIsolation->tag_dipasang_sistem == 'ya' ? '✓' : '' }}</span>
                    Tag Dipasang
                </div>
                <div class="checklist-item">
                    <span class="checkbox {{ $hraLotoIsolation->sekring_cb_sistem_dimatikan == 'ya' ? 'checked' : '' }}">{{ $hraLotoIsolation->sekring_cb_sistem_dimatikan == 'ya' ? '✓' : '' }}</span>
                    Sekring/CB Dimatikan
                </div>
                <div class="checklist-item">
                    <span class="checkbox {{ $hraLotoIsolation->sudah_dicoba_dinyalakan == 'ya' ? 'checked' : '' }}">{{ $hraLotoIsolation->sudah_dicoba_dinyalakan == 'ya' ? '✓' : '' }}</span>
                    Sudah Dicoba Dinyalakan
                </div>
            </div>
        </div>
    </div>

    <!-- Tes Listrik -->
    <div class="section">
        <div class="section-title">TES LISTRIK</div>
        
        <div style="margin-bottom: 12px;">
            <div class="checklist-item">
                <span class="checkbox {{ $hraLotoIsolation->membutuhkan_tes_listrik_on == 'ya' ? 'checked' : '' }}">{{ $hraLotoIsolation->membutuhkan_tes_listrik_on == 'ya' ? '✓' : '' }}</span>
                <strong>Membutuhkan tes dengan listrik ON?</strong>
            </div>
        </div>
        
        @if($hraLotoIsolation->membutuhkan_tes_listrik_on == 'ya')
        <div class="two-column">
            <div class="column">
                <div style="font-weight: bold; margin-bottom: 6px; text-decoration: underline;">Safety Equipment:</div>
                <div class="checklist-item">
                    <span class="checkbox {{ $hraLotoIsolation->safety_barrier == 'ya' ? 'checked' : '' }}">{{ $hraLotoIsolation->safety_barrier == 'ya' ? '✓' : '' }}</span>
                    Safety Barrier
                </div>
                <div class="checklist-item">
                    <span class="checkbox {{ $hraLotoIsolation->full_face_protection == 'ya' ? 'checked' : '' }}">{{ $hraLotoIsolation->full_face_protection == 'ya' ? '✓' : '' }}</span>
                    Full Face Protection
                </div>
                <div class="checklist-item">
                    <span class="checkbox {{ $hraLotoIsolation->insulated_gloves == 'ya' ? 'checked' : '' }}">{{ $hraLotoIsolation->insulated_gloves == 'ya' ? '✓' : '' }}</span>
                    Insulated Gloves
                </div>
                <div class="checklist-item">
                    <span class="checkbox {{ $hraLotoIsolation->insulated_mat == 'ya' ? 'checked' : '' }}">{{ $hraLotoIsolation->insulated_mat == 'ya' ? '✓' : '' }}</span>
                    Insulated Mat
                </div>
                <div class="checklist-item">
                    <span class="checkbox {{ $hraLotoIsolation->full_length_sleeves == 'ya' ? 'checked' : '' }}">{{ $hraLotoIsolation->full_length_sleeves == 'ya' ? '✓' : '' }}</span>
                    Full Length Sleeves
                </div>
                <div class="checklist-item">
                    <span class="checkbox {{ $hraLotoIsolation->tool_insulation_satisfactory == 'ya' ? 'checked' : '' }}">{{ $hraLotoIsolation->tool_insulation_satisfactory == 'ya' ? '✓' : '' }}</span>
                    Tool Insulation Satisfactory
                </div>
            </div>
            <div class="column">
                <div style="font-weight: bold; margin-bottom: 6px; text-decoration: underline;">Test Information:</div>
                <div style="margin-bottom: 12px;">
                    <div class="row">
                        <span class="label">Maximum Voltage (V):</span>
                        <span style="border-bottom: 1px solid #000; display: inline-block; min-width: 80px; text-align: center;">
                            {{ $hraLotoIsolation->maximum_voltage ?? '-' }}
                        </span>
                    </div>
                </div>
                <div class="long-text-row">
                    <div class="long-text-label">Alasan Live Test:</div>
                    <div class="long-text-content" style="border: 1px solid #000; padding: 6px; min-height: 60px; background-color: #f9f9f9;">
                        {{ $hraLotoIsolation->alasan_live_test ?? '-' }}
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Isolasi Utility -->
    <div class="section">
        <div class="section-title">ISOLASI UTILITY</div>
        <table class="table utility-table">
            <thead>
                <tr>
                    <th style="width: 25%;">Medium</th>
                    <th style="width: 18.75%;">Off</th>
                    <th style="width: 18.75%;">Secured/Locked</th>
                    <th style="width: 18.75%;">Checked</th>
                    <th style="width: 18.75%;">Tagged</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="text-left">Listrik</td>
                    <td><span class="checkbox {{ $hraLotoIsolation->utility_listrik_off ? 'checked' : '' }}">{{ $hraLotoIsolation->utility_listrik_off ? '✓' : '' }}</span></td>
                    <td><span class="checkbox {{ $hraLotoIsolation->utility_listrik_secured ? 'checked' : '' }}">{{ $hraLotoIsolation->utility_listrik_secured ? '✓' : '' }}</span></td>
                    <td><span class="checkbox {{ $hraLotoIsolation->utility_listrik_checked ? 'checked' : '' }}">{{ $hraLotoIsolation->utility_listrik_checked ? '✓' : '' }}</span></td>
                    <td><span class="checkbox {{ $hraLotoIsolation->utility_listrik_tagged ? 'checked' : '' }}">{{ $hraLotoIsolation->utility_listrik_tagged ? '✓' : '' }}</span></td>
                </tr>
                <tr>
                    <td class="text-left">Cooling water</td>
                    <td><span class="checkbox {{ $hraLotoIsolation->utility_cooling_water_off ? 'checked' : '' }}">{{ $hraLotoIsolation->utility_cooling_water_off ? '✓' : '' }}</span></td>
                    <td><span class="checkbox {{ $hraLotoIsolation->utility_cooling_water_secured ? 'checked' : '' }}">{{ $hraLotoIsolation->utility_cooling_water_secured ? '✓' : '' }}</span></td>
                    <td><span class="checkbox {{ $hraLotoIsolation->utility_cooling_water_checked ? 'checked' : '' }}">{{ $hraLotoIsolation->utility_cooling_water_checked ? '✓' : '' }}</span></td>
                    <td><span class="checkbox {{ $hraLotoIsolation->utility_cooling_water_tagged ? 'checked' : '' }}">{{ $hraLotoIsolation->utility_cooling_water_tagged ? '✓' : '' }}</span></td>
                </tr>
                <tr>
                    <td class="text-left">Oil Hidrolik</td>
                    <td><span class="checkbox {{ $hraLotoIsolation->utility_oil_hidrolik_off ? 'checked' : '' }}">{{ $hraLotoIsolation->utility_oil_hidrolik_off ? '✓' : '' }}</span></td>
                    <td><span class="checkbox {{ $hraLotoIsolation->utility_oil_hidrolik_secured ? 'checked' : '' }}">{{ $hraLotoIsolation->utility_oil_hidrolik_secured ? '✓' : '' }}</span></td>
                    <td><span class="checkbox {{ $hraLotoIsolation->utility_oil_hidrolik_checked ? 'checked' : '' }}">{{ $hraLotoIsolation->utility_oil_hidrolik_checked ? '✓' : '' }}</span></td>
                    <td><span class="checkbox {{ $hraLotoIsolation->utility_oil_hidrolik_tagged ? 'checked' : '' }}">{{ $hraLotoIsolation->utility_oil_hidrolik_tagged ? '✓' : '' }}</span></td>
                </tr>
                <tr>
                    <td class="text-left">Kompresor</td>
                    <td><span class="checkbox {{ $hraLotoIsolation->utility_kompresor_off ? 'checked' : '' }}">{{ $hraLotoIsolation->utility_kompresor_off ? '✓' : '' }}</span></td>
                    <td><span class="checkbox {{ $hraLotoIsolation->utility_kompresor_secured ? 'checked' : '' }}">{{ $hraLotoIsolation->utility_kompresor_secured ? '✓' : '' }}</span></td>
                    <td><span class="checkbox {{ $hraLotoIsolation->utility_kompresor_checked ? 'checked' : '' }}">{{ $hraLotoIsolation->utility_kompresor_checked ? '✓' : '' }}</span></td>
                    <td><span class="checkbox {{ $hraLotoIsolation->utility_kompresor_tagged ? 'checked' : '' }}">{{ $hraLotoIsolation->utility_kompresor_tagged ? '✓' : '' }}</span></td>
                </tr>
                <tr>
                    <td class="text-left">Vacuum</td>
                    <td><span class="checkbox {{ $hraLotoIsolation->utility_vacuum_off ? 'checked' : '' }}">{{ $hraLotoIsolation->utility_vacuum_off ? '✓' : '' }}</span></td>
                    <td><span class="checkbox {{ $hraLotoIsolation->utility_vacuum_secured ? 'checked' : '' }}">{{ $hraLotoIsolation->utility_vacuum_secured ? '✓' : '' }}</span></td>
                    <td><span class="checkbox {{ $hraLotoIsolation->utility_vacuum_checked ? 'checked' : '' }}">{{ $hraLotoIsolation->utility_vacuum_checked ? '✓' : '' }}</span></td>
                    <td><span class="checkbox {{ $hraLotoIsolation->utility_vacuum_tagged ? 'checked' : '' }}">{{ $hraLotoIsolation->utility_vacuum_tagged ? '✓' : '' }}</span></td>
                </tr>
                <tr>
                    <td class="text-left">Gas</td>
                    <td><span class="checkbox {{ $hraLotoIsolation->utility_gas_off ? 'checked' : '' }}">{{ $hraLotoIsolation->utility_gas_off ? '✓' : '' }}</span></td>
                    <td><span class="checkbox {{ $hraLotoIsolation->utility_gas_secured ? 'checked' : '' }}">{{ $hraLotoIsolation->utility_gas_secured ? '✓' : '' }}</span></td>
                    <td><span class="checkbox {{ $hraLotoIsolation->utility_gas_checked ? 'checked' : '' }}">{{ $hraLotoIsolation->utility_gas_checked ? '✓' : '' }}</span></td>
                    <td><span class="checkbox {{ $hraLotoIsolation->utility_gas_tagged ? 'checked' : '' }}">{{ $hraLotoIsolation->utility_gas_tagged ? '✓' : '' }}</span></td>
                </tr>
                <tr>
                    <td class="text-left">{{ $hraLotoIsolation->utility_lainnya_nama ?: 'Lainnya' }}</td>
                    <td><span class="checkbox {{ $hraLotoIsolation->utility_lainnya_off ? 'checked' : '' }}">{{ $hraLotoIsolation->utility_lainnya_off ? '✓' : '' }}</span></td>
                    <td><span class="checkbox {{ $hraLotoIsolation->utility_lainnya_secured ? 'checked' : '' }}">{{ $hraLotoIsolation->utility_lainnya_secured ? '✓' : '' }}</span></td>
                    <td><span class="checkbox {{ $hraLotoIsolation->utility_lainnya_checked ? 'checked' : '' }}">{{ $hraLotoIsolation->utility_lainnya_checked ? '✓' : '' }}</span></td>
                    <td><span class="checkbox {{ $hraLotoIsolation->utility_lainnya_tagged ? 'checked' : '' }}">{{ $hraLotoIsolation->utility_lainnya_tagged ? '✓' : '' }}</span></td>
                </tr>
            </tbody>
        </table>
        
        <div class="checklist-item">
            <span class="checkbox {{ $hraLotoIsolation->line_diisolasi_plat == 'ya' ? 'checked' : '' }}">{{ $hraLotoIsolation->line_diisolasi_plat == 'ya' ? '✓' : '' }}</span>
            <strong>Line di isolasi dengan plat</strong>
        </div>
        
        <div class="long-text-row">
            <div class="long-text-label">Jika "Tidak" berikan alasan dan deskripsikan bagaimana isolasi dilakukan:</div>
            <div class="long-text-content">{{ $hraLotoIsolation->alasan_deskripsi_isolasi ?? '-' }}</div>
        </div>
        
        <div class="long-text-row">
            <div class="long-text-label">Area yang terdampak isolasi:</div>
            <div class="long-text-content">{{ $hraLotoIsolation->area_terdampak_isolasi ?? '-' }}</div>
        </div>
        
        <div class="checklist-item">
            <span class="checkbox {{ $hraLotoIsolation->area_sudah_diberitahu == 'ya' ? 'checked' : '' }}">{{ $hraLotoIsolation->area_sudah_diberitahu == 'ya' ? '✓' : '' }}</span>
            <strong>Area sudah diberitahu</strong>
        </div>
    </div>

    <!-- Mematikan Pipa -->
    <div class="section">
        <div class="section-title">MEMATIKAN PIPA</div>
        <div class="long-text-row">
            <div class="long-text-label">Isi dari line/pipa:</div>
            <div class="long-text-content">{{ $hraLotoIsolation->isi_line_pipa ?? '-' }}</div>
        </div>
        
        <div class="two-column">
            <div class="checklist-item">
                <span class="checkbox {{ $hraLotoIsolation->tidak_ada_sisa_tekanan == 'ya' ? 'checked' : '' }}">{{ $hraLotoIsolation->tidak_ada_sisa_tekanan == 'ya' ? '✓' : '' }}</span>
                Tidak ada sisa tekanan dalam pipa
            </div>
            <div class="checklist-item">
                <span class="checkbox {{ $hraLotoIsolation->drain_bleed_valves == 'ya' ? 'checked' : '' }}">{{ $hraLotoIsolation->drain_bleed_valves == 'ya' ? '✓' : '' }}</span>
                Drain/bleed valves terbuka dan tidak terblok
            </div>
            <div style="margin-top: 8px;">
                <strong>Pipa di-purged dengan:</strong>
                <div class="badge-list">
                    @if($hraLotoIsolation->pipa_purged_udara)
                        <span class="badge">Udara</span>
                    @endif
                    @if($hraLotoIsolation->pipa_purged_air)
                        <span class="badge">Air</span>
                    @endif
                    @if($hraLotoIsolation->pipa_purged_nitrogen)
                        <span class="badge">Nitrogen</span>
                    @endif
                    @if(!$hraLotoIsolation->pipa_purged_udara && !$hraLotoIsolation->pipa_purged_air && !$hraLotoIsolation->pipa_purged_nitrogen)
                        <span class="badge">-</span>
                    @endif
                </div>
            </div>
            <div class="checklist-item">
                <span class="checkbox {{ $hraLotoIsolation->pipa_diisolasi_plat == 'ya' ? 'checked' : '' }}">{{ $hraLotoIsolation->pipa_diisolasi_plat == 'ya' ? '✓' : '' }}</span>
                Pipa diisolasi dengan plat
            </div>
        </div>
        <div class="two-column">
            <div class="checklist-item">
                <span class="checkbox {{ $hraLotoIsolation->pipa_kosong == 'ya' ? 'checked' : '' }}">{{ $hraLotoIsolation->pipa_kosong == 'ya' ? '✓' : '' }}</span>
                Pipa Kosong
            </div>
            <div class="checklist-item">
                <span class="checkbox {{ $hraLotoIsolation->pipa_bersih == 'ya' ? 'checked' : '' }}">{{ $hraLotoIsolation->pipa_bersih == 'ya' ? '✓' : '' }}</span>
                Pipa Bersih
            </div>
        </div>
        
        <div class="long-text-row">
            <div class="long-text-label">Jika "Tidak" berikan alasan dan deskripsikan bagaimana isolasi dilakukan:</div>
            <div class="long-text-content">{{ $hraLotoIsolation->alasan_deskripsi_isolasi_pipa ?? '-' }}</div>
        </div>
    </div>

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
        <div>HRA LOTO/Isolation Assessment - {{ $hraLotoIsolation->hra_permit_number }}</div>
    </div>
</body>
</html>