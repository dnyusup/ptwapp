<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>HRA LOTO/Isolation - {{ $hraLotoIsolation->hra_permit_number }}</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            margin: 8px; 
            color: black;
            font-size: 9px;
        }
        .header { 
            text-align: center; 
            margin-bottom: 6px;
            border-bottom: 1px solid black;
            padding-bottom: 4px;
        }
        .title { 
            font-size: 12px; 
            font-weight: bold; 
        }
        .section { 
            margin-bottom: 4px; 
            border: 1px solid black;
            padding: 4px;
        }
        .section-title { 
            font-weight: bold; 
            font-size: 10px;
            margin-bottom: 4px;
            background-color: #f5f5f5;
            padding: 2px 4px;
            margin: -4px -4px 4px -4px;
        }
        .row { 
            margin-bottom: 1px; 
        }
        .label { 
            font-weight: bold; 
            display: inline-block;
            width: 120px;
        }
        .long-text-row {
            margin-bottom: 3px;
        }
        .long-text-label {
            font-weight: bold;
            display: block;
            margin-bottom: 1px;
        }
        .long-text-content {
            border: 1px solid #ccc;
            padding: 2px;
            background-color: #f9f9f9;
            min-height: 12px;
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
        .status-badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 3px;
            font-weight: bold;
            font-size: 8px;
            text-transform: uppercase;
        }
        .status-draft { background-color: #6c757d; color: white; }
        .status-active { background-color: #28a745; color: white; }
        .status-completed { background-color: #17a2b8; color: white; }
        .status-cancelled { background-color: #dc3545; color: white; }
    </style>
</head>
<body>
    <div class="header">
        <div style="display: table; width: 100%; margin-bottom: 2px;">
            <div style="display: table-cell; width: 60px; vertical-align: top;">
                @if(isset($qrCode))
                    <img src="data:image/svg+xml;base64,{{ $qrCode }}" alt="QR Code" style="width: 50px; height: 50px;">
                @else
                    <div style="width: 50px; height: 50px; border: 1px solid black; text-align: center; font-size: 6px; padding: 3px; background: white;">
                        <div style="font-weight: bold;">QR CODE</div>
                        <div style="font-size: 5px;">{{ $hraLotoIsolation->hra_permit_number }}</div>
                    </div>
                @endif
            </div>
            <div style="display: table-cell; text-align: center; vertical-align: middle; padding-left: 10px;">
                <div class="title">HRA LOTO/ISOLATION ASSESSMENT</div>
                <div style="font-size: 10px; margin-top: 2px;">{{ $hraLotoIsolation->hra_permit_number }}</div>
                <div style="font-size: 8px;">Main Permit: {{ $permit->permit_number }}</div>
            </div>
            <div style="display: table-cell; width: 70px; text-align: right; vertical-align: top; font-size: 7px;">
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

    <!-- Pre Isolation -->
    <div class="section">
        <div class="section-title">PRE ISOLATION</div>
        <div class="row">
            <span style="font-weight: bold;">P&ID/Rencana Kelistrikan Ditinjau:</span>
            <strong style="margin-left: 10px;">{{ $hraLotoIsolation->pid_reviewed == 'ya' ? 'Ya' : 'Tidak' }}</strong>
        </div>
    </div>

    <!-- Electrical Isolation -->
    <div class="section">
        <div class="section-title">ELECTRICAL ISOLATION</div>
        @if($hraLotoIsolation->electrical_enabled)
        <div class="row">
            <span style="font-weight: bold;">Sedang mengerjakan instalasi HV?</span>
            <strong style="margin-left: 10px;">{{ $hraLotoIsolation->electrical_hv_installation == 'ya' ? 'Ya' : 'Tidak' }}</strong>
        </div>
        
        @php
            $electricalIsolations = json_decode($hraLotoIsolation->electrical_isolations, true) ?? [];
            $hasData = count(array_filter($electricalIsolations, function($item) { return !empty($item['description']); })) > 0;
        @endphp
        
        @if($hasData)
        <table style="width: 100%; border-collapse: collapse; margin-top: 4px; font-size: 7px;">
            <thead>
                <tr style="background-color: #f0f0f0;">
                    <th style="border: 1px solid #333; padding: 2px; width: 20px;">No.</th>
                    <th style="border: 1px solid #333; padding: 2px;">Isolation Description</th>
                    <th style="border: 1px solid #333; padding: 2px; width: 45px; text-align: center;">Stop & Isolate</th>
                    <th style="border: 1px solid #333; padding: 2px; width: 45px; text-align: center;">Lock & Tag</th>
                    <th style="border: 1px solid #333; padding: 2px; width: 45px; text-align: center;">Zero Energy</th>
                    <th style="border: 1px solid #333; padding: 2px; width: 40px; text-align: center;">Try Out</th>
                    <th style="border: 1px solid #333; padding: 2px; width: 40px; text-align: center;">Removal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($electricalIsolations as $index => $isolation)
                    @if(!empty($isolation['description']))
                    <tr>
                        <td style="border: 1px solid #333; padding: 2px; text-align: center;">E{{ $index }}</td>
                        <td style="border: 1px solid #333; padding: 2px;">{{ $isolation['description'] }}</td>
                        <td style="border: 1px solid #333; padding: 2px; text-align: center;">{{ !empty($isolation['stop_isolate']) ? 'v' : '-' }}</td>
                        <td style="border: 1px solid #333; padding: 2px; text-align: center;">{{ !empty($isolation['lock_tag']) ? 'v' : '-' }}</td>
                        <td style="border: 1px solid #333; padding: 2px; text-align: center;">{{ !empty($isolation['zero_energy']) ? 'v' : '-' }}</td>
                        <td style="border: 1px solid #333; padding: 2px; text-align: center;">{{ !empty($isolation['try_out']) ? 'v' : '-' }}</td>
                        <td style="border: 1px solid #333; padding: 2px; text-align: center;">{{ !empty($isolation['removal']) ? 'v' : '-' }}</td>
                    </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
        @endif
        
        @if($hraLotoIsolation->electrical_energy_control_method)
        <div class="long-text-row" style="margin-top: 4px;">
            <div class="long-text-label">Metode untuk mengendalikan energi yang tersimpan:</div>
            <div class="long-text-content">{{ $hraLotoIsolation->electrical_energy_control_method }}</div>
        </div>
        @endif
        @else
        <div class="row"><em>Tidak ada data isolasi</em></div>
        @endif
    </div>

    <!-- Mechanical Isolation -->
    <div class="section">
        <div class="section-title">MECHANICAL ISOLATION</div>
        @if($hraLotoIsolation->mechanical_enabled)
        <div class="two-column" style="margin-bottom: 4px; font-size: 8px;">
            <div class="column">
                <div class="row"><span style="font-weight:bold; width:70px; display:inline-block;">Gravitasi:</span> {{ $hraLotoIsolation->mechanical_gravitasi == 'ya' ? 'Ya' : 'Tidak' }}</div>
                <div class="row"><span style="font-weight:bold; width:70px; display:inline-block;">Kelembaman:</span> {{ $hraLotoIsolation->mechanical_kelembaman == 'ya' ? 'Ya' : 'Tidak' }}</div>
                <div class="row"><span style="font-weight:bold; width:70px; display:inline-block;">Pneumatik:</span> {{ $hraLotoIsolation->mechanical_pneumatik == 'ya' ? 'Ya' : 'Tidak' }}</div>
            </div>
            <div class="column">
                <div class="row"><span style="font-weight:bold; width:70px; display:inline-block;">Hidrolik:</span> {{ $hraLotoIsolation->mechanical_hidrolik == 'ya' ? 'Ya' : 'Tidak' }}</div>
                <div class="row"><span style="font-weight:bold; width:70px; display:inline-block;">Spring:</span> {{ $hraLotoIsolation->mechanical_spring == 'ya' ? 'Ya' : 'Tidak' }}</div>
                @if($hraLotoIsolation->mechanical_lainnya)
                <div class="row"><span style="font-weight:bold; width:70px; display:inline-block;">Lainnya:</span> {{ $hraLotoIsolation->mechanical_lainnya }}</div>
                @endif
            </div>
        </div>
        
        @php
            $mechanicalIsolations = json_decode($hraLotoIsolation->mechanical_isolations, true) ?? [];
            $hasMechData = count(array_filter($mechanicalIsolations, function($item) { return !empty($item['description']); })) > 0;
        @endphp
        
        @if($hasMechData)
        <table style="width: 100%; border-collapse: collapse; margin-top: 4px; font-size: 7px;">
            <thead>
                <tr style="background-color: #f0f0f0;">
                    <th style="border: 1px solid #333; padding: 2px; width: 20px;">No.</th>
                    <th style="border: 1px solid #333; padding: 2px;">Isolation Description</th>
                    <th style="border: 1px solid #333; padding: 2px; width: 45px; text-align: center;">Stop & Isolate</th>
                    <th style="border: 1px solid #333; padding: 2px; width: 45px; text-align: center;">Lock & Tag</th>
                    <th style="border: 1px solid #333; padding: 2px; width: 45px; text-align: center;">Zero Energy</th>
                    <th style="border: 1px solid #333; padding: 2px; width: 40px; text-align: center;">Try Out</th>
                    <th style="border: 1px solid #333; padding: 2px; width: 40px; text-align: center;">Removal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($mechanicalIsolations as $index => $isolation)
                    @if(!empty($isolation['description']))
                    <tr>
                        <td style="border: 1px solid #333; padding: 2px; text-align: center;">M{{ $index }}</td>
                        <td style="border: 1px solid #333; padding: 2px;">{{ $isolation['description'] }}</td>
                        <td style="border: 1px solid #333; padding: 2px; text-align: center;">{{ !empty($isolation['stop_isolate']) ? 'v' : '-' }}</td>
                        <td style="border: 1px solid #333; padding: 2px; text-align: center;">{{ !empty($isolation['lock_tag']) ? 'v' : '-' }}</td>
                        <td style="border: 1px solid #333; padding: 2px; text-align: center;">{{ !empty($isolation['zero_energy']) ? 'v' : '-' }}</td>
                        <td style="border: 1px solid #333; padding: 2px; text-align: center;">{{ !empty($isolation['try_out']) ? 'v' : '-' }}</td>
                        <td style="border: 1px solid #333; padding: 2px; text-align: center;">{{ !empty($isolation['removal']) ? 'v' : '-' }}</td>
                    </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
        @endif
        
        @if($hraLotoIsolation->mechanical_energy_control_method)
        <div class="long-text-row" style="margin-top: 4px;">
            <div class="long-text-label">Metode untuk mengendalikan energi yang tersimpan:</div>
            <div class="long-text-content">{{ $hraLotoIsolation->mechanical_energy_control_method }}</div>
        </div>
        @endif
        @else
        <div class="row"><em>Tidak ada data isolasi</em></div>
        @endif
    </div>

    <!-- Process Isolation -->
    <div class="section">
        <div class="section-title">PROCESS ISOLATION</div>
        @if($hraLotoIsolation->process_enabled)
        @php
            $processIsolations = json_decode($hraLotoIsolation->process_isolations, true) ?? [];
            $hasProcData = count(array_filter($processIsolations, function($item) { return !empty($item['description']); })) > 0;
        @endphp
        
        @if($hasProcData)
        <table style="width: 100%; border-collapse: collapse; font-size: 7px;">
            <thead>
                <tr style="background-color: #f0f0f0;">
                    <th style="border: 1px solid #333; padding: 2px; width: 20px;">No.</th>
                    <th style="border: 1px solid #333; padding: 2px;">Isolation Description</th>
                    <th style="border: 1px solid #333; padding: 2px; width: 45px; text-align: center;">Stop & Isolate</th>
                    <th style="border: 1px solid #333; padding: 2px; width: 45px; text-align: center;">Lock & Tag</th>
                    <th style="border: 1px solid #333; padding: 2px; width: 45px; text-align: center;">Zero Energy</th>
                    <th style="border: 1px solid #333; padding: 2px; width: 40px; text-align: center;">Try Out</th>
                    <th style="border: 1px solid #333; padding: 2px; width: 40px; text-align: center;">Removal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($processIsolations as $index => $isolation)
                    @if(!empty($isolation['description']))
                    <tr>
                        <td style="border: 1px solid #333; padding: 2px; text-align: center;">P{{ $index }}</td>
                        <td style="border: 1px solid #333; padding: 2px;">{{ $isolation['description'] }}</td>
                        <td style="border: 1px solid #333; padding: 2px; text-align: center;">{{ !empty($isolation['stop_isolate']) ? 'v' : '-' }}</td>
                        <td style="border: 1px solid #333; padding: 2px; text-align: center;">{{ !empty($isolation['lock_tag']) ? 'v' : '-' }}</td>
                        <td style="border: 1px solid #333; padding: 2px; text-align: center;">{{ !empty($isolation['zero_energy']) ? 'v' : '-' }}</td>
                        <td style="border: 1px solid #333; padding: 2px; text-align: center;">{{ !empty($isolation['try_out']) ? 'v' : '-' }}</td>
                        <td style="border: 1px solid #333; padding: 2px; text-align: center;">{{ !empty($isolation['removal']) ? 'v' : '-' }}</td>
                    </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
        @else
        <div class="row"><em>Tidak ada data isolasi</em></div>
        @endif
        
        @if($hraLotoIsolation->process_energy_control_method)
        <div class="long-text-row" style="margin-top: 4px;">
            <div class="long-text-label">Metode untuk mengendalikan energi yang tersimpan:</div>
            <div class="long-text-content">{{ $hraLotoIsolation->process_energy_control_method }}</div>
        </div>
        @endif
        @else
        <div class="row"><em>Tidak ada data isolasi</em></div>
        @endif
    </div>

    <!-- Utility Isolation -->
    <div class="section">
        <div class="section-title">UTILITY ISOLATION</div>
        @if($hraLotoIsolation->utility_enabled)
        @php
            $utilityIsolations = json_decode($hraLotoIsolation->utility_isolations, true) ?? [];
            $hasUtilData = count(array_filter($utilityIsolations, function($item) { return !empty($item['description']); })) > 0;
        @endphp
        
        @if($hasUtilData)
        <table style="width: 100%; border-collapse: collapse; font-size: 7px;">
            <thead>
                <tr style="background-color: #f0f0f0;">
                    <th style="border: 1px solid #333; padding: 2px; width: 20px;">No.</th>
                    <th style="border: 1px solid #333; padding: 2px;">Isolation Description</th>
                    <th style="border: 1px solid #333; padding: 2px; width: 45px; text-align: center;">Stop & Isolate</th>
                    <th style="border: 1px solid #333; padding: 2px; width: 45px; text-align: center;">Lock & Tag</th>
                    <th style="border: 1px solid #333; padding: 2px; width: 45px; text-align: center;">Zero Energy</th>
                    <th style="border: 1px solid #333; padding: 2px; width: 40px; text-align: center;">Try Out</th>
                    <th style="border: 1px solid #333; padding: 2px; width: 40px; text-align: center;">Removal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($utilityIsolations as $index => $isolation)
                    @if(!empty($isolation['description']))
                    <tr>
                        <td style="border: 1px solid #333; padding: 2px; text-align: center;">U{{ $index }}</td>
                        <td style="border: 1px solid #333; padding: 2px;">{{ $isolation['description'] }}</td>
                        <td style="border: 1px solid #333; padding: 2px; text-align: center;">{{ !empty($isolation['stop_isolate']) ? 'v' : '-' }}</td>
                        <td style="border: 1px solid #333; padding: 2px; text-align: center;">{{ !empty($isolation['lock_tag']) ? 'v' : '-' }}</td>
                        <td style="border: 1px solid #333; padding: 2px; text-align: center;">{{ !empty($isolation['zero_energy']) ? 'v' : '-' }}</td>
                        <td style="border: 1px solid #333; padding: 2px; text-align: center;">{{ !empty($isolation['try_out']) ? 'v' : '-' }}</td>
                        <td style="border: 1px solid #333; padding: 2px; text-align: center;">{{ !empty($isolation['removal']) ? 'v' : '-' }}</td>
                    </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
        @else
        <div class="row"><em>Tidak ada data isolasi</em></div>
        @endif
        
        @if($hraLotoIsolation->utility_energy_control_method)
        <div class="long-text-row" style="margin-top: 4px;">
            <div class="long-text-label">Metode untuk mengendalikan energi yang tersimpan:</div>
            <div class="long-text-content">{{ $hraLotoIsolation->utility_energy_control_method }}</div>
        </div>
        @endif
        @else
        <div class="row"><em>Tidak ada data isolasi</em></div>
        @endif
    </div>

    <!-- Verifikasi Isolasi -->
    <div class="section">
        <div class="section-title">VERIFIKASI ISOLASI</div>
        <div class="row">
            <span class="label">Daerah yang terpengaruh:</span>
            {{ $hraLotoIsolation->affected_area ?? 'N/A' }}
        </div>
        <div class="row" style="font-size: 8px;">
            <span style="display: inline-block; width: 80%;">Semua individu yang terkena dampak diberitahu tentang isolasi</span>
            <strong>{{ $hraLotoIsolation->all_individuals_informed == 'ya' ? 'Ya' : 'Tidak' }}</strong>
        </div>
        <div class="row" style="font-size: 8px;">
            <span style="display: inline-block; width: 80%;">Semua orang yang bekerja HARUS LOTOTO secara individual dengan kunci pribadi</span>
            <strong>{{ $hraLotoIsolation->individual_lototo_required == 'ya' ? 'Ya' : 'Tidak' }}</strong>
        </div>
        <div class="row" style="font-size: 8px;">
            <span style="display: inline-block; width: 80%;">PtW Issuer HARUS memiliki kunci LOTOTO pada setiap isolasi</span>
            <strong>{{ $hraLotoIsolation->ptw_issuer_lototo_key == 'ya' ? 'Ya' : 'Tidak' }}</strong>
        </div>
    </div>

    <!-- Line Breaking -->
    <div class="section">
        <div class="section-title">LINE BREAKING</div>
        <div class="row">
            <span class="label">Konten baris sebelumnya:</span>
            {{ $hraLotoIsolation->line_content_before ?? 'N/A' }}
        </div>
        <div style="display: table; width: 100%; font-size: 8px;">
            <div style="display: table-cell; width: 50%; vertical-align: top; padding-right: 5px;">
                <div class="row">
                    <span style="display: inline-block; width: 70%;">Tidak ada tekanan sisa di saluran</span>
                    <strong>{{ $hraLotoIsolation->lb_no_residual_pressure == 'ya' ? 'Ya' : 'Tidak' }}</strong>
                </div>
                <div class="row">
                    <span style="display: inline-block; width: 70%;">Katup pembuangan terbuka</span>
                    <strong>{{ $hraLotoIsolation->lb_drain_valve_open == 'ya' ? 'Ya' : 'Tidak' }}</strong>
                </div>
                <div class="row">
                    <span style="display: inline-block; width: 70%;">Emergency arrangements</span>
                    <strong>{{ $hraLotoIsolation->lb_emergency_arrangements == 'ya' ? 'Ya' : 'Tidak' }}</strong>
                </div>
                <div class="row">
                    <span style="display: inline-block; width: 70%;">Garis diisolasi dengan pelat/sekop</span>
                    <strong>{{ $hraLotoIsolation->lb_line_isolated == 'ya' ? 'Ya' : 'Tidak' }}</strong>
                </div>
                <div class="row">
                    <span style="display: inline-block; width: 70%;">Garisnya kosong</span>
                    <strong>{{ $hraLotoIsolation->lb_line_empty == 'ya' ? 'Ya' : 'Tidak' }}</strong>
                </div>
                <div class="row">
                    <span style="display: inline-block; width: 70%;">Garisnya bersih</span>
                    <strong>{{ $hraLotoIsolation->lb_line_clean == 'ya' ? 'Ya' : 'Tidak' }}</strong>
                </div>
            </div>
            <div style="display: table-cell; width: 50%; vertical-align: top;">
                <div class="row">
                    <span style="display: inline-block; width: 70%;">Tidak ada serat asbes/keramik</span>
                    <strong>{{ $hraLotoIsolation->lb_no_asbestos == 'ya' ? 'Ya' : 'Tidak' }}</strong>
                </div>
                <div class="row">
                    <span style="display: inline-block; width: 70%;">Saluran/pipa tidak butuh dukungan</span>
                    <strong>{{ $hraLotoIsolation->lb_pipe_no_support_needed == 'ya' ? 'Ya' : 'Tidak' }}</strong>
                </div>
                <div class="row">
                    <span style="display: inline-block; width: 70%;">LoToTo/ pengurasan reservoir</span>
                    <strong>{{ $hraLotoIsolation->lb_lototo_drainage == 'ya' ? 'Ya' : 'Tidak' }}</strong>
                </div>
                <div class="row">
                    <span style="display: inline-block; width: 70%;">Line purged with:</span>
                    <strong>
                        @if($hraLotoIsolation->lb_purged_air) Air @endif
                        @if($hraLotoIsolation->lb_purged_water) Water @endif
                        @if($hraLotoIsolation->lb_purged_n2) N2 @endif
                        @if(!$hraLotoIsolation->lb_purged_air && !$hraLotoIsolation->lb_purged_water && !$hraLotoIsolation->lb_purged_n2) - @endif
                    </strong>
                </div>
            </div>
        </div>
        @if($hraLotoIsolation->lb_additional_control)
        <div class="long-text-row" style="margin-top: 3px;">
            <div class="long-text-label">Alasan dan kontrol tambahan:</div>
            <div class="long-text-content">{{ $hraLotoIsolation->lb_additional_control }}</div>
        </div>
        @endif
    </div>

    <!-- Main Permit Information -->
    <div class="section">
        <div class="section-title">MAIN PERMIT INFORMATION</div>
        <div style="display: table; width: 100%;">
            <div style="display: table-cell; width: 75%; vertical-align: top; padding-right: 10px;">
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
            <div style="display: table-cell; width: 25%; vertical-align: top; text-align: center;">
                @if(isset($permitQrCode))
                    <img src="data:image/svg+xml;base64,{{ $permitQrCode }}" alt="Main Permit QR Code" style="width: 45px; height: 45px;">
                @else
                    <div style="width: 45px; height: 45px; border: 1px solid black; text-align: center; font-size: 5px; padding: 2px; background: white; margin: 0 auto;">
                        <div style="font-weight: bold;">QR CODE</div>
                        <div>{{ $permit->permit_number }}</div>
                    </div>
                @endif
                <div style="font-size: 6px;">Main Permit</div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div style="margin-top: 8px; text-align: center; font-size: 7px; color: #666;">
        <div>Generated on {{ date('d/m/Y H:i:s') }} | HRA LOTO/Isolation - {{ $hraLotoIsolation->hra_permit_number }}</div>
    </div>
</body>
</html>
