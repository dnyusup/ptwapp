<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Permit Extension Request</title>
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 30px 40px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }
        
        .email-header h1 {
            color: #ffffff !important;
            font-size: 24px !important;
            font-weight: bold !important;
            margin: 0 !important;
            padding: 0 !important;
        }
        
        .email-header .subtitle {
            color: #f0f0f0 !important;
            font-size: 14px !important;
            margin-top: 5px !important;
        }
        
        /* Content styles */
        .email-content {
            padding: 40px;
        }
        
        .greeting {
            font-size: 16px;
            margin-bottom: 20px;
            color: #333333;
        }
        
        .info-table {
            width: 100% !important;
            border-collapse: collapse !important;
            margin: 20px 0 !important;
            background-color: #f8f9fa;
            border-radius: 8px;
            overflow: hidden;
        }
        
        .info-table th,
        .info-table td {
            padding: 12px 15px !important;
            text-align: left !important;
            border-bottom: 1px solid #dee2e6 !important;
        }
        
        .info-table th {
            background-color: #e9ecef !important;
            font-weight: bold !important;
            color: #495057 !important;
            width: 35%;
        }
        
        .info-table td {
            color: #333333 !important;
        }
        
        .info-table tr:last-child th,
        .info-table tr:last-child td {
            border-bottom: none !important;
        }
        
        /* Extension specific styles */
        .extension-highlight {
            background-color: #fff3cd !important;
            border: 2px solid #ffeaa7 !important;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        
        .extension-highlight h3 {
            color: #856404 !important;
            margin: 0 0 10px 0 !important;
            font-size: 18px;
        }
        
        .extension-highlight p {
            color: #856404 !important;
            margin: 0 !important;
        }
        
        /* Button styles */
        .button-container {
            text-align: center;
            margin: 30px 0;
        }
        
        .button {
            display: inline-block !important;
            padding: 12px 30px !important;
            background-color: #28a745 !important;
            color: #ffffff !important;
            text-decoration: none !important;
            border-radius: 5px !important;
            font-weight: bold !important;
            margin: 0 10px !important;
        }
        
        .button.danger {
            background-color: #dc3545 !important;
        }
        
        .button:hover {
            opacity: 0.8;
        }
        
        /* Footer styles */
        .email-footer {
            background-color: #f8f9fa;
            padding: 30px 40px;
            text-align: center;
            border-radius: 0 0 10px 10px;
            border-top: 1px solid #dee2e6;
        }
        
        .email-footer p {
            margin: 0 !important;
            font-size: 12px !important;
            color: #6c757d !important;
        }
        
        /* Responsive styles */
        @media only screen and (max-width: 600px) {
            .email-container {
                width: 100% !important;
            }
            
            .email-header,
            .email-content,
            .email-footer {
                padding: 20px !important;
            }
            
            .info-table th,
            .info-table td {
                padding: 8px 10px !important;
                font-size: 12px !important;
            }
            
            .button {
                display: block !important;
                margin: 10px 0 !important;
            }
        }
    </style>
</head>
<body>
    <table class="email-container" cellpadding="0" cellspacing="0" border="0">
        <tr>
            <td>
                <!-- Header -->
                <div class="email-header">
                    <h1>🔄 Permit Extension Request</h1>
                    <p class="subtitle">A permit requires extension approval</p>
                </div>
                
                <!-- Content -->
                <div class="email-content">
                    <p class="greeting">Dear EHS Team,</p>
                    
                    <p>A permit that has expired requires an extension. Please review the details below and approve or reject the extension request.</p>
                    
                    <!-- Extension Information Highlight -->
                    <div class="extension-highlight">
                        <h3>⚠️ Extension Request Details</h3>
                        <p><strong>Original End Date:</strong> {{ \Carbon\Carbon::parse($originalEndDate)->format('d M Y') }}</p>
                        <p><strong>Requested New End Date:</strong> {{ \Carbon\Carbon::parse($newEndDate)->format('d M Y') }}</p>
                        <p><strong>Extension Duration:</strong> {{ \Carbon\Carbon::parse($originalEndDate)->diffInDays(\Carbon\Carbon::parse($newEndDate)) }} day(s)</p>
                    </div>
                    
                    <!-- Permit Information -->
                    <table class="info-table">
                        <tr>
                            <th>Permit Number</th>
                            <td>{{ $permit->permit_number }}</td>
                        </tr>
                        <tr>
                            <th>Work Title</th>
                            <td>{{ $permit->work_title }}</td>
                        </tr>
                        <tr>
                            <th>Work Location</th>
                            <td>{{ $permit->work_location }}</td>
                        </tr>
                        <tr>
                            <th>Department</th>
                            <td>{{ $permit->department }}</td>
                        </tr>
                        <tr>
                            <th>Permit Creator</th>
                            <td>
                                {{ $permit->permitIssuer->name ?? 'N/A' }}<br>
                                <small style="color: #6c757d;">{{ $permit->permitIssuer->email ?? '' }}</small>
                            </td>
                        </tr>
                        <tr>
                            <th>Responsible Person</th>
                            <td>
                                {{ $permit->responsible_person ?? 'N/A' }}<br>
                                @if($permit->responsible_person_email)
                                <small style="color: #6c757d;">{{ $permit->responsible_person_email }}</small>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Original Start Date</th>
                            <td>{{ $permit->start_date ? $permit->start_date->format('d M Y') : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Work Description</th>
                            <td>{{ Str::limit($permit->work_description, 200) ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Current Status</th>
                            <td>
                                <span style="background-color: #ffc107; color: #000; padding: 2px 8px; border-radius: 3px; font-weight: bold;">
                                    PENDING EXTENSION APPROVAL
                                </span>
                            </td>
                        </tr>
                    </table>
                    
                    <div class="button-container">
                        <p style="margin-bottom: 20px; color: #666;">Click the link below to review and approve/reject this permit extension:</p>
                        <a href="{{ route('permits.show', $permit->id) }}" class="button">
                            📋 Review Permit Extension
                        </a>
                    </div>
                    
                    <p style="margin-top: 30px; padding: 15px; background-color: #e7f3ff; border-left: 4px solid #007bff; border-radius: 4px;">
                        <strong>📝 Note:</strong> This permit was originally expired and the creator has requested an extension. Please review the work progress and safety conditions before approving the extension.
                    </p>
                </div>
                
                <!-- Footer -->
                <div class="email-footer">
                    <p>This is an automated message from the Permit to Work System.</p>
                    <p>Please do not reply to this email. For support, contact your system administrator.</p>
                    <p style="margin-top: 15px;">&copy; {{ date('Y') }} Bekaert. All rights reserved.</p>
                </div>
            </td>
        </tr>
    </table>
</body>
</html>