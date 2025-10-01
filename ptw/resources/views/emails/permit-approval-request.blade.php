<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Permit Approval Request</title>
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
            background-color: #007bff;
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
            background-color: #ffc107;
            color: #212529;
            padding: 6px 12px;
            border-radius: 4px;
            font-weight: bold;
            display: inline-block;
        }
        
        /* Section headers */
        .section-header {
            background-color: #007bff;
            color: #ffffff;
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
        }
        
        /* Action button */
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
                            <h1>🔔 Permit Approval Request</h1>
                        </td>
                    </tr>
                    
                    <!-- Content -->
                    <tr>
                        <td class="email-content">
                            <p>Dear EHS Team,</p>
                            <p>A new permit has been submitted and requires your approval. Please review the details below:</p>
                            
                            <!-- Permit Details Section -->
                            <div class="section-header">📋 Permit Details</div>
                            <table class="info-table" role="presentation" cellspacing="0" cellpadding="0" border="0">
                                <tr>
                                    <td class="info-label">Permit Number:</td>
                                    <td class="info-value">{{ $permit->permit_number }}</td>
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
                                    <td class="info-label">Work Period:</td>
                                    <td class="info-value">{{ \Carbon\Carbon::parse($permit->work_start_date)->format('d M Y') }} - {{ \Carbon\Carbon::parse($permit->work_end_date)->format('d M Y') }}</td>
                                </tr>
                                <tr>
                                    <td class="info-label">Responsible Person:</td>
                                    <td class="info-value">{{ $permit->responsible_person }}</td>
                                </tr>
                                <tr>
                                    <td class="info-label">Contractor:</td>
                                    <td class="info-value">{{ $permit->receiver_company_name }}</td>
                                </tr>
                                <tr>
                                    <td class="info-label">Status:</td>
                                    <td class="info-value">
                                        <span class="status-badge">{{ strtoupper(str_replace('_', ' ', $permit->status)) }}</span>
                                    </td>
                                </tr>
                            </table>
                            
                            <!-- Work Description Section -->
                            @if($permit->work_description)
                            <div class="section-header">🛠️ Work Description</div>
                            <div class="description-box">
                                {{ $permit->work_description }}
                            </div>
                            @endif
                            
                            <!-- Equipment & Tools Section -->
                            @if($permit->equipment_tools)
                            <div class="section-header">🔧 Equipment & Tools</div>
                            <div class="description-box">
                                {{ $permit->equipment_tools }}
                            </div>
                            @endif
                            
            <!-- Action Required -->
            <div class="action-section">
                <div class="action-text">⚠️ Action Required:</div>
                <p>Please review and approve this permit as soon as possible to avoid work delays.</p>
                
                <!-- Action Button -->
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" style="margin: 20px auto;">
                    <tr>
                        <td style="text-align: center;">
                            <a href="{{ url('/permits/' . $permit->id) }}" 
                               style="background-color: #007bff; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px; font-weight: bold; display: inline-block;">
                                📋 View Permit Details
                            </a>
                        </td>
                    </tr>
                </table>
            </div>                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td class="email-footer">
                            <p>This email was sent automatically from the PTW Portal System.<br>
                            Please do not reply to this email.</p>
                        </td>
                    </tr>
                    
                </table>
            </td>
        </tr>
    </table>
</body>
</html>