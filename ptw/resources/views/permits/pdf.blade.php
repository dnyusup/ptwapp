<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Permit to Work - {{ $permit->permit_number }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            color: #000 !important;
            line-height: 1.4;
        }
        * {
            color: #000 !important;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #333;
        }
        .company-logo {
            font-size: 24px;
            font-weight: bold;
            color: #000 !important;
            margin-bottom: 5px;
        }
        .document-title {
            font-size: 18px;
            font-weight: bold;
            margin: 10px 0;
            color: #000 !important;
        }
        .permit-number {
            font-size: 14px;
            color: #000 !important;
        }
        .section {
            margin-bottom: 25px;
        }
        .section-title {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 15px;
            padding: 8px 0;
            border-bottom: 1px solid #ddd;
            color: #000 !important;
        }
        .info-grid {
            width: 100%;
            margin-bottom: 15px;
        }
        .info-row {
            margin-bottom: 8px;
            overflow: hidden;
        }
        .info-label {
            float: left;
            width: 30%;
            padding: 8px 0;
            font-weight: bold;
            color: #000 !important;
        }
        .info-value {
            float: left;
            width: 70%;
            padding: 8px 0;
            color: #000 !important;
        }
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 15px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
            color: #000 !important;
        }
        .status-active {
            background-color: #e5f9f0;
            color: #000 !important;
        }
        .status-completed {
            background-color: #e5f2ff;
            color: #000 !important;
        }
        .checkbox-list {
            margin: 10px 0;
            width: 100%;
        }
        .checkbox-item {
            margin: 5px 0;
            padding: 3px 0;
            color: #000 !important;
        }
        .checkbox {
            display: inline-block;
            width: 12px;
            height: 12px;
            border: 1px solid #333;
            margin-right: 8px;
            text-align: center;
            vertical-align: middle;
        }
        .checkbox.checked {
            background-color: #333;
            color: white;
        }
        .signatures {
            margin-top: 40px;
            page-break-before: avoid;
        }
        .signature-grid {
            width: 100%;
            margin-top: 30px;
            overflow: hidden;
        }
        .signature-row {
            margin-bottom: 20px;
            overflow: hidden;
        }
        .signature-cell {
            float: left;
            width: 33.33%;
            padding: 20px 10px;
            text-align: center;
            color: #000 !important;
        }
        .signature-line {
            border-top: 1px solid #333;
            margin-top: 50px;
            padding-top: 5px;
            color: #000 !important;
        }
        .signature-title {
            font-weight: bold;
            margin-bottom: 5px;
            color: #000 !important;
        }
        .signature-name {
            margin-bottom: 3px;
            color: #000 !important;
        }
        .signature-date {
            font-size: 10px;
            color: #666;
        }
        .digital-stamp {
            border: 2px solid #1e40af;
            padding: 10px;
            margin: 10px 0;
            background-color: #f0f4ff;
            text-align: center;
            font-size: 11px;
        }
        .page-break {
            page-break-before: always;
        }
        .method-statement {
            margin-top: 20px;
        }
        .risk-table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        .risk-table th,
        .risk-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            font-size: 11px;
        }
        .risk-table th {
            background-color: #f5f5f5;
            font-weight: bold;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            font-size: 10px;
            color: #666;
            text-align: center;
        }
    </style>
</head>
<body>
    <!-- Page 1: Permit Details -->
    <div class="header">
        <div class="company-logo">PERMIT TO WORK SYSTEM</div>
        <div class="document-title">PERMIT TO WORK</div>
        <div class="permit-number">{{ $permit->permit_number }}</div>
    </div>

    <div class="section">
        <div class="section-title">Permit Information</div>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Status:</div>
                <div class="info-value">
                    <span class="status-badge status-{{ $permit->status }}">
                        {{ strtoupper($permit->status) }}
                    </span>
                </div>
            </div>
            <div class="info-row">
                <div class="info-label">Work Title:</div>
                <div class="info-value">{{ $permit->work_title ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Department:</div>
                <div class="info-value">{{ $permit->department }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Work Location:</div>
                <div class="info-value">{{ $permit->work_location }}</div>
            </div>
            @if($permit->locationOwner)
            <div class="info-row">
                <div class="info-label">Location Owner:</div>
                <div class="info-value">{{ $permit->locationOwner->name }} ({{ $permit->locationOwner->email }})</div>
            </div>
            @endif
        </div>
    </div>

    <div class="section">
        <div class="section-title">Work Details</div>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Responsible Person:</div>
                <div class="info-value">{{ $permit->responsible_person }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Work Description:</div>
                <div class="info-value">{{ $permit->work_description ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Equipment & Tools:</div>
                <div class="info-value">{{ $permit->equipment_tools }}</div>
            </div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Work Schedule</div>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Start Date:</div>
                <div class="info-value">{{ $permit->start_date ? $permit->start_date->format('d M Y') : 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">End Date:</div>
                <div class="info-value">{{ $permit->end_date ? $permit->end_date->format('d M Y') : 'N/A' }}</div>
            </div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Work Type Classification</div>
        <div class="checkbox-list">
            <div class="checkbox-item">
                <span class="checkbox {{ $permit->work_at_heights ? 'checked' : '' }}">{{ $permit->work_at_heights ? '✓' : '' }}</span>
                Work at Heights
            </div>
            <div class="checkbox-item">
                <span class="checkbox {{ $permit->hot_work ? 'checked' : '' }}">{{ $permit->hot_work ? '✓' : '' }}</span>
                Hot Work
            </div>
            <div class="checkbox-item">
                <span class="checkbox {{ $permit->loto_isolation ? 'checked' : '' }}">{{ $permit->loto_isolation ? '✓' : '' }}</span>
                LOTO/Isolation
            </div>
            <div class="checkbox-item">
                <span class="checkbox {{ $permit->line_breaking ? 'checked' : '' }}">{{ $permit->line_breaking ? '✓' : '' }}</span>
                Line Breaking
            </div>
            <div class="checkbox-item">
                <span class="checkbox {{ $permit->excavation ? 'checked' : '' }}">{{ $permit->excavation ? '✓' : '' }}</span>
                Excavation
            </div>
            <div class="checkbox-item">
                <span class="checkbox {{ $permit->confined_spaces ? 'checked' : '' }}">{{ $permit->confined_spaces ? '✓' : '' }}</span>
                Confined Spaces
            </div>
            <div class="checkbox-item">
                <span class="checkbox {{ $permit->explosive_atmosphere ? 'checked' : '' }}">{{ $permit->explosive_atmosphere ? '✓' : '' }}</span>
                Explosive Atmosphere
            </div>
        </div>
    </div>

    <!-- Digital Signatures Section -->
    <div class="signatures">
        <div class="section-title">Digital Signatures & Approvals</div>
        
        <div class="digital-stamp">
            <strong>DIGITALLY SIGNED DOCUMENT</strong><br>
            Generated on: {{ now()->format('d M Y H:i:s') }} WIB<br>
            Document authenticated by PTW System
        </div>

        <div class="signature-grid">
            <div class="signature-row">
                <div class="signature-cell">
                    <div class="signature-title">PERMIT ISSUER</div>
                    <div class="signature-name">{{ $permit->permitIssuer->name ?? 'N/A' }}</div>
                    <div class="signature-line">Digitally Signed</div>
                    <div class="signature-date">
                        {{ $permit->created_at ? $permit->created_at->format('d M Y H:i') : 'N/A' }}
                    </div>
                </div>
                <div class="signature-cell">
                    <div class="signature-title">WORK RECEIVER</div>
                    <div class="signature-name">
                        @if($permit->receiver && $permit->receiver->name)
                            {{ $permit->receiver->name }}
                        @elseif($permit->receiver_name)
                            {{ $permit->receiver_name }}
                        @else
                            N/A
                        @endif
                    </div>
                    <div class="signature-line">Digitally Signed</div>
                    <div class="signature-date">
                        {{ $permit->methodStatement && $permit->methodStatement->created_at ? $permit->methodStatement->created_at->format('d M Y H:i') : 'N/A' }}
                    </div>
                </div>
                <div class="signature-cell">
                    <div class="signature-title">AUTHORIZER/SHE</div>
                    <div class="signature-name">{{ $permit->authorizer->name ?? 'N/A' }}</div>
                    <div class="signature-line">Digitally Signed</div>
                    <div class="signature-date">
                        {{ $permit->authorized_at ? $permit->authorized_at->format('d M Y H:i') : 'N/A' }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Page 2: Method Statement -->
    @if($permit->methodStatement)
    <div class="page-break">
        <div class="header">
            <div class="company-logo">METHOD STATEMENT</div>
            <div class="document-title">RISK ASSESSMENT & METHOD STATEMENT</div>
            <div class="permit-number">{{ $permit->permit_number }}</div>
        </div>

        <div class="method-statement">
            <div class="section">
                <div class="section-title">Method Statement Information</div>
                <div class="info-grid">
                    <div class="info-row">
                        <div class="info-label">Responsible Person:</div>
                        <div class="info-value">{{ $permit->methodStatement->responsible_person_name ?? 'N/A' }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Date:</div>
                        <div class="info-value">{{ $permit->methodStatement->method_statement_date ? \Carbon\Carbon::parse($permit->methodStatement->method_statement_date)->format('d M Y') : 'N/A' }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Permit Receiver:</div>
                        <div class="info-value">{{ $permit->methodStatement->permit_receiver_name ?? 'N/A' }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Status:</div>
                        <div class="info-value">{{ ucfirst($permit->methodStatement->status ?? 'N/A') }}</div>
                    </div>
                </div>
            </div>

            <!-- Responsible Persons Section -->
            @php
                $hasResponsiblePersons = false;
                for($i = 1; $i <= 6; $i++) {
                    if($permit->methodStatement->{'responsible_person_' . $i}) {
                        $hasResponsiblePersons = true;
                        break;
                    }
                }
            @endphp
            
            @if($hasResponsiblePersons)
            <div class="section">
                <div class="section-title">Responsible Persons</div>
                <div class="info-grid">
                    @for($i = 1; $i <= 6; $i++)
                        @if($permit->methodStatement->{'responsible_person_' . $i})
                        <div class="info-row">
                            <div class="info-label">{{ $i }}.</div>
                            <div class="info-value">{{ $permit->methodStatement->{'responsible_person_' . $i} }}</div>
                        </div>
                        @endif
                    @endfor
                </div>
            </div>
            @endif

            <!-- Work Method Explanations -->
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
                
                $hasExplanations = false;
                foreach($explanations as $field => $label) {
                    if($permit->methodStatement->$field) {
                        $hasExplanations = true;
                        break;
                    }
                }
            @endphp
            
            @if($hasExplanations)
            <div class="section">
                <div class="section-title">Work Method Explanations</div>
                @foreach($explanations as $field => $label)
                    @if($permit->methodStatement->$field)
                    <div style="margin-bottom: 15px;">
                        <div style="font-weight: bold; color: #1e40af; margin-bottom: 5px;">{{ $label }}</div>
                        <div style="padding: 8px; background-color: #f8fafc; border-left: 3px solid #1e40af; font-size: 11px;">
                            {{ $permit->methodStatement->$field }}
                        </div>
                    </div>
                    @endif
                @endforeach
            </div>
            @endif

            @if($permit->methodStatement->risk_activities)
            <div class="section">
                <div class="section-title">Risk Assessment</div>
                <table class="risk-table">
                    <thead>
                        <tr>
                            <th style="width: 40%">Activity</th>
                            <th style="width: 20%">Risk Level</th>
                            <th style="width: 40%">Control Measures</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $activities = is_string($permit->methodStatement->risk_activities) 
                                ? json_decode($permit->methodStatement->risk_activities, true) 
                                : $permit->methodStatement->risk_activities;
                            $riskLevels = is_string($permit->methodStatement->risk_levels) 
                                ? json_decode($permit->methodStatement->risk_levels, true) 
                                : $permit->methodStatement->risk_levels;
                            $controlMeasures = is_string($permit->methodStatement->control_measures) 
                                ? json_decode($permit->methodStatement->control_measures, true) 
                                : $permit->methodStatement->control_measures;
                        @endphp
                        @if($activities && is_array($activities))
                            @foreach($activities as $index => $activity)
                            <tr>
                                <td>{{ $activity ?? 'N/A' }}</td>
                                <td>{{ $riskLevels[$index] ?? 'N/A' }}</td>
                                <td>{{ $controlMeasures[$index] ?? 'N/A' }}</td>
                            </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="3" style="text-align: center;">No risk assessment data available</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </div>
    @endif

    <div class="footer">
        <p>This document was generated electronically by the Permit to Work System on {{ now()->format('d M Y H:i:s') }} WIB</p>
        <p>Document Reference: {{ $permit->permit_number }} | Status: {{ strtoupper($permit->status) }}</p>
    </div>
</body>
</html>
