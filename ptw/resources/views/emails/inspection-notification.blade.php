<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inspection Report</title>
    <!--[if mso]>
    <noscript>
        <xml>
            <o:OfficeDocumentSettings>
                <o:AllowPNG/>
                <o:PixelsPerInch>96</o:PixelsPerInch>
            </o:OfficeDocumentSettings>
        </xml>
    </noscript>
    <![endif]-->
    <style>
        /* Reset styles for email clients */
        body, table, td, p, a, li, blockquote {
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }
        table, td {
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }
        img {
            -ms-interpolation-mode: bicubic;
        }

        /* Main styles */
        body {
            margin: 0 !important;
            padding: 0 !important;
            background-color: #f4f4f4 !important;
            font-family: Arial, Helvetica, sans-serif !important;
            font-size: 14px;
            line-height: 1.6;
            color: #333333;
        }
        
        /* Container table for Outlook */
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
        }
        
        /* Header styles */
        .email-header {
            background-color: #dc3545;
            color: #ffffff;
            padding: 20px;
            text-align: center;
        }
        
        .email-header h1 {
            margin: 0;
            padding: 0;
            font-size: 24px;
            font-weight: bold;
            color: #ffffff;
        }
        
        /* Content area */
        .email-content {
            padding: 30px;
        }
        
        /* Info table styles */
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
        }
        
        .info-table td {
            padding: 12px 15px;
            border-bottom: 1px solid #dee2e6;
            vertical-align: top;
        }
        
        .info-label {
            font-weight: bold;
            width: 35%;
            color: #495057;
            background-color: #e9ecef;
        }
        
        .info-value {
            width: 65%;
            color: #212529;
        }
        
        /* Status badge */
        .status-badge {
            background-color: #28a745;
            color: #ffffff;
            padding: 6px 12px;
            border-radius: 4px;
            font-weight: bold;
            display: inline-block;
        }
        
        .status-pending { background-color: #ffc107; color: #212529; }
        .status-expired { background-color: #dc3545; color: #ffffff; }
        .status-completed { background-color: #17a2b8; color: #ffffff; }
        
        /* Section headers */
        .section-header {
            background-color: #dc3545;
            color: #ffffff;
            padding: 10px 15px;
            font-weight: bold;
            margin: 20px 0 0 0;
        }
        
        /* Inspection section */
        .inspection-section {
            background-color: #fff3cd;
            color: #856404;
            padding: 10px 15px;
            font-weight: bold;
            margin: 20px 0 0 0;
        }
        
        /* Description box */
        .description-box {
            background-color: #f8f9fa;
            padding: 15px;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            margin: 10px 0;
            white-space: pre-wrap;
            font-family: 'Courier New', monospace;
        }
        
        /* Action section */
        .action-section {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 4px;
            padding: 20px;
            margin: 20px 0;
            text-align: center;
        }
        
        .action-text {
            color: #856404;
            font-weight: bold;
            margin-bottom: 10px;
        }
        
        /* Footer */
        .email-footer {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            border-top: 1px solid #dee2e6;
            font-size: 12px;
            color: #6c757d;
        }
        
        /* Risk level badges */
        .risk-level {
            padding: 4px 8px;
            border-radius: 4px;
            font-weight: bold;
            font-size: 12px;
            text-transform: uppercase;
        }
        
        .risk-low { background-color: #d4edda; color: #155724; }
        .risk-medium { background-color: #fff3cd; color: #856404; }
        .risk-high { background-color: #f8d7da; color: #721c24; }
        .risk-critical { background-color: #f5c6cb; color: #721c24; }
        
        /* Outlook specific fixes */
        .outlook-fix {
            mso-line-height-rule: exactly;
        }
    </style>
</head>
<body>
    <!-- Wrapper table for Outlook -->
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: #f4f4f4;">
        <tr>
            <td align="center" style="padding: 20px 0;">
                <!-- Main email container -->
                <table class="email-container" role="presentation" cellspacing="0" cellpadding="0" border="0">
                    
                    <!-- Header -->
                    <tr>
                        <td class="email-header">
                            <h1>🔍 Inspection Report</h1>
                        </td>
                    </tr>
                    
                    <!-- Content -->
                    <tr>
                        <td class="email-content">
                            <p>Dear EHS Team,</p>
                            <p>A new inspection report has been submitted. Please review the details below:</p>
                            
                            <!-- Permit Details Section -->
                            <div class="section-header">📋 Permit Details</div>
                            <table class="info-table" role="presentation" cellspacing="0" cellpadding="0" border="0">
                                <tr>
                                    <td class="info-label">Permit Number:</td>
                                    <td class="info-value"><strong>{{ $permit->permit_number }}</strong></td>
                                </tr>
                                <tr>
                                    <td class="info-label">Work Title:</td>
                                    <td class="info-value">{{ $permit->work_title }}</td>
                                </tr>
                                <tr>
                                    <td class="info-label">Location:</td>
                                    <td class="info-value">{{ $permit->work_location }}</td>
                                </tr>
                                <tr>
                                    <td class="info-label">Department:</td>
                                    <td class="info-value">{{ $permit->department }}</td>
                                </tr>
                                <tr>
                                    <td class="info-label">Work Period:</td>
                                    <td class="info-value">{{ $permit->start_date->format('d M Y') }} - {{ $permit->end_date->format('d M Y') }}</td>
                                </tr>
                                <tr>
                                    <td class="info-label">Current Status:</td>
                                    <td class="info-value">
                                        <span class="status-badge status-{{ $permit->status }}">
                                            @if($permit->status === 'active')
                                                ACTIVE
                                            @elseif($permit->status === 'pending_approval')
                                                PENDING APPROVAL
                                            @elseif($permit->status === 'expired')
                                                EXPIRED
                                            @elseif($permit->status === 'completed')
                                                COMPLETED
                                            @else
                                                {{ strtoupper(str_replace('_', ' ', $permit->status)) }}
                                            @endif
                                        </span>
                                    </td>
                                </tr>
                                @if($permit->risk_level)
                                <tr>
                                    <td class="info-label">Risk Level:</td>
                                    <td class="info-value">
                                        <span class="risk-level risk-{{ $permit->risk_level }}">{{ ucfirst($permit->risk_level) }}</span>
                                    </td>
                                </tr>
                                @endif
                                <tr>
                                    <td class="info-label">Permit Issuer:</td>
                                    <td class="info-value">{{ $permit->permitIssuer ? $permit->permitIssuer->name : 'N/A' }}</td>
                                </tr>
                            </table>
                            
                            <!-- Inspection Details Section -->
                            <div class="inspection-section">🔍 Inspection Details</div>
                            <table class="info-table" role="presentation" cellspacing="0" cellpadding="0" border="0">
                                <tr>
                                    <td class="info-label">Inspector:</td>
                                    <td class="info-value"><strong>{{ $inspection->inspector_name }}</strong></td>
                                </tr>
                                <tr>
                                    <td class="info-label">Inspector Email:</td>
                                    <td class="info-value">{{ $inspection->inspector_email }}</td>
                                </tr>
                                <tr>
                                    <td class="info-label">Inspection Date:</td>
                                    <td class="info-value">{{ $inspection->created_at->format('d M Y H:i') }}</td>
                                </tr>
                            </table>
                            
                            <!-- Findings Section -->
                            @if($inspection->findings)
                            <div class="inspection-section">📝 Inspection Findings</div>
                            <div class="description-box">
                                {{ $inspection->findings }}
                            </div>
                            @endif
                            
                            <!-- Photo Section -->
                            @if(isset($hasPhoto) && $hasPhoto && $inspection->photo_path)
                            <div class="inspection-section">📷 Foto Inspeksi</div>
                            <div style="background-color: #f8f9fa; padding: 15px; border: 1px solid #dee2e6; border-radius: 4px; margin: 10px 0; text-align: center;">
                                <img src="{{ $message->embed(storage_path('app/public/' . $inspection->photo_path)) }}" 
                                     alt="Foto Inspeksi" 
                                     width="150"
                                     style="width: 150px; height: auto; border-radius: 4px; border: 1px solid #dee2e6;">
                                <p style="margin: 10px 0 0 0; font-size: 12px; color: #6c757d;">Foto diambil pada {{ $inspection->created_at->format('d M Y H:i') }}</p>
                            </div>
                            @endif
                            
                            <!-- Action Required -->
                            <div class="action-section">
                                <div class="action-text">⚠️ Review Required:</div>
                                <p>Please review this inspection report and take appropriate actions based on the findings.</p>
                                
                                <!-- Action Button -->
                                <table role="presentation" cellspacing="0" cellpadding="0" border="0" style="margin: 20px auto;">
                                    <tr>
                                        <td style="text-align: center;">
                                            <a href="{{ url('/permits/' . $permit->id) }}" 
                                               style="background-color: #dc3545; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px; font-weight: bold; display: inline-block;">
                                                🔍 View Permit Details
                                            </a>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td class="email-footer">
                            <p>This email was sent automatically from the PTW Portal System.<br>
                            Please do not reply to this email.</p>
                            <p><strong>Note:</strong> This email was sent to EHS team with CC to area owner and inspector.</p>
                        </td>
                    </tr>
                    
                </table>
            </td>
        </tr>
    </table>
</body>
</html>