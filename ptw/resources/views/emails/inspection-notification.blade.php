<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inspection Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .header {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        
        .header p {
            margin: 5px 0 0 0;
            opacity: 0.9;
        }
        
        .content {
            padding: 30px 20px;
        }
        
        .permit-info {
            background-color: #f8f9fa;
            border-left: 4px solid #007bff;
            padding: 20px;
            margin-bottom: 25px;
            border-radius: 0 4px 4px 0;
        }
        
        .permit-info h3 {
            margin: 0 0 15px 0;
            color: #007bff;
            font-size: 18px;
        }
        
        .info-grid {
            display: table;
            width: 100%;
            border-collapse: collapse;
        }
        
        .info-row {
            display: table-row;
        }
        
        .info-label, .info-value {
            display: table-cell;
            padding: 8px 0;
            vertical-align: top;
        }
        
        .info-label {
            font-weight: 600;
            color: #555;
            width: 35%;
            padding-right: 15px;
        }
        
        .info-value {
            color: #333;
        }
        
        .inspection-section {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 6px;
            padding: 20px;
            margin-bottom: 25px;
        }
        
        .inspection-section h3 {
            margin: 0 0 15px 0;
            color: #856404;
            font-size: 18px;
        }
        
        .findings-box {
            background-color: #ffffff;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            padding: 15px;
            margin-top: 10px;
            white-space: pre-wrap;
            font-family: 'Courier New', monospace;
            font-size: 14px;
            line-height: 1.5;
        }
        
        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .status-active { background-color: #d4edda; color: #155724; }
        .status-pending { background-color: #fff3cd; color: #856404; }
        .status-expired { background-color: #f8d7da; color: #721c24; }
        .status-completed { background-color: #d1ecf1; color: #0c5460; }
        
        .footer {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            border-top: 1px solid #dee2e6;
        }
        
        .footer p {
            margin: 0;
            color: #6c757d;
            font-size: 14px;
        }
        
        .risk-level {
            font-weight: 600;
            text-transform: uppercase;
            font-size: 12px;
            padding: 4px 8px;
            border-radius: 12px;
        }
        
        .risk-low { background-color: #d4edda; color: #155724; }
        .risk-medium { background-color: #fff3cd; color: #856404; }
        .risk-high { background-color: #f8d7da; color: #721c24; }
        .risk-critical { background-color: #f5c6cb; color: #721c24; }
        
        @media only screen and (max-width: 600px) {
            .email-container {
                margin: 0;
                border-radius: 0;
            }
            
            .content {
                padding: 20px 15px;
            }
            
            .info-grid {
                display: block;
            }
            
            .info-row {
                display: block;
                margin-bottom: 10px;
            }
            
            .info-label, .info-value {
                display: block;
                width: 100%;
                padding: 2px 0;
            }
            
            .info-label {
                font-weight: 600;
                margin-bottom: 2px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>🔍 Inspection Report</h1>
            <p>New inspection has been conducted</p>
        </div>
        
        <div class="content">
            <div class="permit-info">
                <h3>Permit Information</h3>
                <div class="info-grid">
                    <div class="info-row">
                        <div class="info-label">Permit Number:</div>
                        <div class="info-value"><strong>{{ $permit->permit_number }}</strong></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Work Title:</div>
                        <div class="info-value">{{ $permit->work_title }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Location:</div>
                        <div class="info-value">{{ $permit->work_location }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Department:</div>
                        <div class="info-value">{{ $permit->department }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Work Period:</div>
                        <div class="info-value">{{ $permit->start_date->format('d M Y H:i') }} - {{ $permit->end_date->format('d M Y H:i') }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Current Status:</div>
                        <div class="info-value">
                            <span class="status-badge status-{{ $permit->status }}">
                                @if($permit->status === 'active')
                                    Active
                                @elseif($permit->status === 'pending_approval')
                                    Pending Approval
                                @elseif($permit->status === 'expired')
                                    Expired
                                @elseif($permit->status === 'completed')
                                    Completed
                                @else
                                    {{ ucwords(str_replace('_', ' ', $permit->status)) }}
                                @endif
                            </span>
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Risk Level:</div>
                        <div class="info-value">
                            <span class="risk-level risk-{{ $permit->risk_level }}">{{ ucfirst($permit->risk_level) }}</span>
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Permit Issuer:</div>
                        <div class="info-value">{{ $permit->permitIssuer ? $permit->permitIssuer->name : 'N/A' }}</div>
                    </div>
                </div>
            </div>
            
            <div class="inspection-section">
                <h3>Inspection Details</h3>
                <div class="info-grid">
                    <div class="info-row">
                        <div class="info-label">Inspector:</div>
                        <div class="info-value"><strong>{{ $inspection->inspector_name }}</strong></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Inspector Email:</div>
                        <div class="info-value">{{ $inspection->inspector_email }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Inspection Date:</div>
                        <div class="info-value">{{ $inspection->created_at->format('d M Y H:i') }}</div>
                    </div>
                </div>
                
                <div style="margin-top: 15px;">
                    <div class="info-label">Findings:</div>
                    <div class="findings-box">{{ $inspection->findings }}</div>
                </div>
            </div>
            
            <p style="margin-bottom: 10px; color: #6c757d;">
                This inspection report has been automatically generated and sent to the EHS team for review. 
                Please take appropriate actions based on the findings.
            </p>
            
            <p style="margin-bottom: 0; color: #6c757d; font-size: 14px;">
                <strong>Note:</strong> This email was sent to EHS team with CC to area owner and inspector.
            </p>
        </div>
        
        <div class="footer">
            <p>&copy; {{ date('Y') }} Permit to Work System. All rights reserved.</p>
            <p>This is an automated notification. Please do not reply to this email.</p>
        </div>
    </div>
</body>
</html>