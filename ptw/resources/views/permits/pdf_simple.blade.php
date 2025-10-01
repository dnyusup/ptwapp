<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Permit to Work - {{ $permit->permit_number }}</title>
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
        }
        .checked { 
            background-color: black; 
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
                        <div style="font-size: 6px; line-height: 1.1;">Scan to view permit details</div>
                        <div style="font-size: 5px; margin-top: 5px; word-wrap: break-word;">{{ $permit->permit_number }}</div>
                    </div>
                @endif
            </div>
            <div style="display: table-cell; text-align: center; vertical-align: middle;">
                <div class="title">PERMIT TO WORK</div>
                <div>Permit Number: {{ $permit->permit_number }}</div>
            </div>
            <div style="display: table-cell; width: 100px; vertical-align: top; text-align: right;">
                @if(file_exists(public_path('images/logo.png')))
                    <img src="{{ public_path('images/logo.png') }}" alt="Company Logo" style="width: 80px; height: 30px;">
                @elseif(file_exists(public_path('images/logo.jpg')))
                    <img src="{{ public_path('images/logo.jpg') }}" alt="Company Logo" style="width: 80px; height: 30px;">
                @elseif(file_exists(public_path('images/company-logo.png')))
                    <img src="{{ public_path('images/company-logo.png') }}" alt="Company Logo" style="width: 80px; height: 20px;">
                @else
                    <div style="width: 80px; height: 30px; border: 1px solid #ccc; text-align: center; font-size: 8px; padding: 5px; background: #f9f9f9;">
                        <div style="margin-top: 8px;">LOGO</div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Permit Information</div>
        <div class="row">
            <span class="label">Status:</span>
            {{ strtoupper($permit->status) }}
        </div>
        <div class="row">
            <span class="label">Work Title:</span>
            {{ $permit->work_title ?? 'N/A' }}
        </div>
        <div class="row">
            <span class="label">Department:</span>
            {{ $permit->department }}
        </div>
        <div class="row">
            <span class="label">Work Location:</span>
            {{ $permit->work_location }}
        </div>
        @if($permit->locationOwner)
        <div class="row">
            <span class="label">Location Owner:</span>
            {{ $permit->locationOwner->name }} ({{ $permit->locationOwner->email }})
        </div>
        @endif
    </div>

    <div class="section">
        <div class="section-title">Work Details</div>
        <div class="row">
            <span class="label">Responsible Person:</span>
            {{ $permit->responsible_person }}
        </div>
        <div class="long-text-row">
            <div class="long-text-label">Work Description:</div>
            <div class="long-text-content">{{ $permit->work_description ?? 'N/A' }}</div>
        </div>
        <div class="long-text-row">
            <div class="long-text-label">Equipment/Tools:</div>
            <div class="long-text-content">{{ $permit->equipment_tools ?? 'N/A' }}</div>
        </div>
        <div class="row">
            <span class="label">Receiver Name:</span>
            {{ $permit->receiver_name ?? 'N/A' }}
        </div>
        <div class="row">
            <span class="label">Receiver Email:</span>
            {{ $permit->receiver_email ?? 'N/A' }}
        </div>
        <div class="row">
            <span class="label">Company Name:</span>
            {{ $permit->receiver_company_name ?? 'N/A' }}
        </div>
    </div>

    <div class="section">
        <div class="section-title">Work Schedule</div>
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td style="width: 50%; padding: 3px; vertical-align: top; border: none;">
                    <div class="row">
                        <span class="label">Start Date:</span>
                        {{ $permit->start_date ? $permit->start_date->format('d/m/Y') : 'N/A' }}
                    </div>
                </td>
                <td style="width: 50%; padding: 3px; vertical-align: top; border: none;">
                    <div class="row">
                        <span class="label">End Date:</span>
                        {{ $permit->end_date ? $permit->end_date->format('d/m/Y') : 'N/A' }}
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Work Type Classification</div>
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td style="width: 50%; padding: 5px; vertical-align: top;">
                    <div class="row">
                        [{{ $permit->work_at_heights ? 'X' : ' ' }}] Bekerja di Ketinggian (Work at Heights)
                    </div>
                    <div class="row">
                        [{{ $permit->hot_work ? 'X' : ' ' }}] Pekerjaan Panas (Hot Work)
                    </div>
                    <div class="row">
                        [{{ $permit->loto_isolation ? 'X' : ' ' }}] LOTOTO - Isolasi/Menghilangkan Energi
                    </div>
                    <div class="row">
                        [{{ $permit->line_breaking ? 'X' : ' ' }}] Mematikan Line (Line breaking) (hidrolik line etc.)
                    </div>
                </td>
                <td style="width: 50%; padding: 5px; vertical-align: top;">
                    <div class="row">
                        [{{ $permit->excavation ? 'X' : ' ' }}] Penggalian (Excavation)
                    </div>
                    <div class="row">
                        [{{ $permit->confined_spaces ? 'X' : ' ' }}] Ruang Terbatas (Entering Confined spaces)
                    </div>
                    <div class="row">
                        [{{ $permit->explosive_atmosphere ? 'X' : ' ' }}] Atmosfer berbahaya (Explosive atmosphere)
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Signatures</div>
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td style="width: 33.33%; border: 1px solid black; padding: 10px; vertical-align: top;">
                    <div style="text-align: center;">
                        <strong>PERMIT ISSUER</strong><br>
                        <div style="margin: 10px 0; font-style: italic; color: #666;">
                            DIGITALLY SIGNED
                        </div>
                        <div style="border-top: 1px solid black; margin-top: 15px; padding-top: 5px;">
                            Name: {{ $permit->permitIssuer->name ?? 'N/A' }}<br>
                            Date: {{ $permit->issued_at ? $permit->issued_at->format('d/m/Y H:i') : $permit->created_at->format('d/m/Y H:i') }}
                        </div>
                    </div>
                </td>
                <td style="width: 33.33%; border: 1px solid black; padding: 10px; vertical-align: top;">
                    <div style="text-align: center;">
                        <strong>RECEIVER / PELAKSANA</strong><br>
                        <div style="margin: 10px 0; font-style: italic; color: #666;">
                            DIGITALLY SIGNED
                        </div>
                        <div style="border-top: 1px solid black; margin-top: 15px; padding-top: 5px;">
                            Name: {{ $permit->receiver_name ?? 'N/A' }}<br>
                            Date: {{ $permit->received_at ? $permit->received_at->format('d/m/Y H:i') : ($permit->methodStatement && $permit->methodStatement->created_at ? $permit->methodStatement->created_at->format('d/m/Y H:i') : '______') }}
                        </div>
                    </div>
                </td>
                <td style="width: 33.33%; border: 1px solid black; padding: 10px; vertical-align: top;">
                    <div style="text-align: center;">
                        <strong>AUTHORIZER / EHS</strong><br>
                        <div style="margin: 10px 0; font-style: italic; color: #666;">
                            @if($permit->authorizer && $permit->authorized_at)
                                DIGITALLY SIGNED
                            @else
                                PENDING AUTHORIZATION
                            @endif
                        </div>
                        <div style="border-top: 1px solid black; margin-top: 15px; padding-top: 5px;">
                            Name: {{ $permit->authorizer->name ?? 'N/A' }}<br>
                            Date: {{ $permit->authorized_at ? $permit->authorized_at->format('d/m/Y H:i') : '______' }}
                        </div>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    @if($permit->methodStatement)
    <div class="page-break"></div>
    
    <!-- Method Statement Header -->
    <div class="header">
        <div class="title">METHOD STATEMENT</div>
        <div>Permit Number: {{ $permit->permit_number }}</div>
    </div>

    <div class="section">
        <div class="section-title">Basic Information</div>
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td style="width: 25%; padding: 8px; border-bottom: 1px solid #ddd; font-weight: bold;">Responsible Person Name:</td>
                <td style="width: 25%; padding: 8px; border-bottom: 1px solid #ddd;">{{ $permit->methodStatement->responsible_person_name ?? 'N/A' }}</td>
                <td style="width: 25%; padding: 8px; border-bottom: 1px solid #ddd; font-weight: bold;">Method Statement Date:</td>
                <td style="width: 25%; padding: 8px; border-bottom: 1px solid #ddd;">{{ $permit->methodStatement->method_statement_date ? $permit->methodStatement->method_statement_date->format('d/m/Y') : 'N/A' }}</td>
            </tr>
            <tr>
                <td style="width: 25%; padding: 8px; border-bottom: 1px solid #ddd; font-weight: bold;">Permit Receiver Name:</td>
                <td style="width: 25%; padding: 8px; border-bottom: 1px solid #ddd;">{{ $permit->methodStatement->permit_receiver_name ?? 'N/A' }}</td>
                <td style="width: 25%; padding: 8px; border-bottom: 1px solid #ddd; font-weight: bold;">Permit Issuer Name:</td>
                <td style="width: 25%; padding: 8px; border-bottom: 1px solid #ddd;">{{ $permit->methodStatement->permit_issuer_name ?? 'N/A' }}</td>
            </tr>
        </table>
    </div>

    <!-- Responsible Persons -->
    @if(!empty($responsiblePersonsWithPhones) || ($permit->methodStatement && $permit->methodStatement->responsible_persons && is_array($permit->methodStatement->responsible_persons) && count($permit->methodStatement->responsible_persons) > 0))
    <div class="section">
        <div class="section-title">Responsible Persons</div>
        <table style="width: 100%; border-collapse: collapse;">
            @php
                $personsData = [];
                
                if (!empty($responsiblePersonsWithPhones)) {
                    $personsData = $responsiblePersonsWithPhones;
                } else if ($permit->methodStatement->responsible_persons && is_array($permit->methodStatement->responsible_persons)) {
                    // Fallback to method statement data
                    foreach ($permit->methodStatement->responsible_persons as $person) {
                        if (is_array($person) && isset($person['email'])) {
                            $personsData[] = [
                                'name' => (string)($person['name'] ?? $person['email']),
                                'email' => (string)$person['email'],
                                'phone' => 'N/A'
                            ];
                        }
                    }
                }
                
                // Split into two columns
                $leftColumn = array_slice($personsData, 0, ceil(count($personsData) / 2));
                $rightColumn = array_slice($personsData, ceil(count($personsData) / 2));
            @endphp
            
            <tr>
                <!-- Left Column -->
                <td style="width: 50%; padding: 10px; vertical-align: top; border-right: 1px solid #ddd;">
                    @foreach($leftColumn as $person)
                        <div style="margin-bottom: 8px; padding: 4px; background-color: #f8f9fa; border-radius: 4px;">
                            <div style="font-weight: bold; margin-bottom: 2px; font-size: 11px;">{{ $person['name'] }}</div>
                            <div style="color: #666; font-size: 9px;">
                                {{ $person['phone'] ?? 'No phone provided' }}
                            </div>
                        </div>
                    @endforeach
                </td>
                
                <!-- Right Column -->
                <td style="width: 50%; padding: 10px; vertical-align: top;">
                    @foreach($rightColumn as $person)
                        <div style="margin-bottom: 8px; padding: 4px; background-color: #f8f9fa; border-radius: 4px;">
                            <div style="font-weight: bold; margin-bottom: 2px; font-size: 11px;">{{ $person['name'] }}</div>
                            <div style="color: #666; font-size: 9px;">
                                {{ $person['phone'] ?? 'No phone provided' }}
                            </div>
                        </div>
                    @endforeach
                </td>
            </tr>
        </table>
    </div>
    @endif

    <!-- Work Method Explanations -->
    <div class="section">
        <div class="section-title">Work Method Explanations</div>
        @php
            $explanations = [
                'work_access_explanation' => 'Cara menuju dan dari lokasi kerja',
                'safety_equipment_explanation' => 'APD dan peralatan safety',
                'training_competency_explanation' => 'Training/kompetensi/pengalaman',
                'route_identification_explanation' => 'Identifikasi rute',
                'work_area_preparation_explanation' => 'Lokasi peralatan dan penyimpanan',
                'work_sequence_explanation' => 'Urutan pekerjaan',
                'equipment_maintenance_explanation' => 'Peralatan yang dibutuhkan',
                'platform_explanation' => 'Platform sementara',
                'hand_washing_explanation' => 'Pengaruh cuaca',
                'work_area_cleanliness_explanation' => 'Kebersihan dan kerapian area kerja'
            ];
        @endphp
        
        @foreach($explanations as $field => $label)
            @if($permit->methodStatement->$field)
            <div style="margin-bottom: 6px;">
                <div style="font-weight: bold; margin-bottom: 2px;">{{ $label }}:</div>
                <div style="margin-left: 10px;">{{ $permit->methodStatement->$field }}</div>
            </div>
            @endif
        @endforeach
    </div>

    @if($permit->methodStatement->risk_activities && count($permit->methodStatement->risk_activities) > 0)
    <div class="section">
        <div class="section-title">Risk Assessment</div>
        <table>
            <thead>
                <tr>
                    <th>Activity</th>
                    <th>Risk Level</th>
                    <th>Control Measures</th>
                </tr>
            </thead>
            <tbody>
                @foreach($permit->methodStatement->risk_activities as $index => $activity)
                <tr>
                    <td>{{ $activity }}</td>
                    <td>{{ $permit->methodStatement->risk_levels[$index] ?? 'N/A' }}</td>
                    <td>{{ $permit->methodStatement->control_measures[$index] ?? 'N/A' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
    @endif

    <div style="text-align: center; margin-top: 20px; font-size: 10px; color: #666;">
        Generated on {{ now()->format('d/m/Y H:i:s') }}
    </div>
</body>
</html>
