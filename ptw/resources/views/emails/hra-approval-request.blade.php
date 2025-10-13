<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HRA Hot Work Approval Request</title>
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
            font-size: 16px !important;
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
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
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
            font-size: 16px !important;
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
        
        /* Section headers */
        .section-header {
            background-color: #dc3545;
            color: #ffffff;
            padding: 10px 15px;
            font-weight: bold;
            margin: 20px 0 0 0;
            font-size: 18px;
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
        
        .action-button {
            background-color: #28a745;
            color: #ffffff !important;
            padding: 15px 30px;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
            display: inline-block;
            margin: 10px 0;
            font-size: 16px;
        }
        
        /* Important note */
        .important-note {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 4px;
            padding: 15px;
            margin: 20px 0;
        }
        
        /* Footer */
        .email-footer {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            border-top: 1px solid #dee2e6;
            font-size: 14px;
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
                            <h1>🔥 HRA Hot Work Approval Request</h1>
                        </td>
                    </tr>
                    
                    <!-- Content -->
                    <tr>
                        <td class="email-content">
                            <p style="font-size: 16px; margin-bottom: 20px;">Dear Location Owner & EHS Team,</p>
                            
                            <p style="font-size: 16px; margin-bottom: 20px;">A new HRA Hot Work permit has been submitted and requires your approval. Please review the details below and take the necessary action.</p>
                            
                            <!-- HRA Hot Work Details Section -->
                            <div class="section-header">🔥 HRA Hot Work Details</div>
                            <table class="info-table" role="presentation" cellspacing="0" cellpadding="0" border="0">
                                <tr>
                                    <td class="info-label">HRA Number:</td>
                                    <td class="info-value"><strong>{{ $hraHotWork->hra_permit_number }}</strong></td>
                                </tr>
                                <tr>
                                    <td class="info-label">Main Permit:</td>
                                    <td class="info-value">{{ $permit->permit_number }}</td>
                                </tr>
                                <tr>
                                    <td class="info-label">Worker Name:</td>
                                    <td class="info-value">{{ $hraHotWork->worker_name }}</td>
                                </tr>
                                <tr>
                                    <td class="info-label">Supervisor:</td>
                                    <td class="info-value">{{ $hraHotWork->supervisor_name }}</td>
                                </tr>
                                <tr>
                                    <td class="info-label">Work Location:</td>
                                    <td class="info-value">{{ $hraHotWork->work_location }}</td>
                                </tr>
                                <tr>
                                    <td class="info-label">Work Period:</td>
                                    <td class="info-value">{{ $hraHotWork->start_datetime->format('d/m/Y H:i') }} - {{ $hraHotWork->end_datetime->format('d/m/Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <td class="info-label">Work Description:</td>
                                    <td class="info-value">{{ $hraHotWork->work_description }}</td>
                                </tr>
                                <tr>
                                    <td class="info-label">Requested by:</td>
                                    <td class="info-value">{{ $permit->user->name ?? 'Unknown' }}</td>
                                </tr>
                                <tr>
                                    <td class="info-label">Request Date:</td>
                                    <td class="info-value">{{ $hraHotWork->approval_requested_at->format('d/m/Y H:i') }}</td>
                                </tr>
                            </table>
                            
                            <!-- Main Permit Information Section -->
                            <div class="section-header">🏢 Main Permit Information</div>
                            <table class="info-table" role="presentation" cellspacing="0" cellpadding="0" border="0">
                                <tr>
                                    <td class="info-label">Location Owner:</td>
                                    <td class="info-value">{{ $permit->locationOwner ? $permit->locationOwner->name : 'Not specified' }}</td>
                                </tr>
                                <tr>
                                    <td class="info-label">Location Owner Email:</td>
                                    <td class="info-value">{{ $permit->locationOwner ? $permit->locationOwner->email : 'Not specified' }}</td>
                                </tr>
                                <tr>
                                    <td class="info-label">Work Title:</td>
                                    <td class="info-value">{{ $permit->work_title }}</td>
                                </tr>
                                <tr>
                                    <td class="info-label">Department:</td>
                                    <td class="info-value">{{ $permit->department }}</td>
                                </tr>
                            </table>
                            
                            <!-- Important Notes -->
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                <tr>
                                    <td class="important-note">
                                        <h4 style="margin-top: 0; color: #856404; font-size: 16px;">⚠️ Important Notes:</h4>
                                        <p style="margin: 10px 0; font-size: 16px;">• Both Location Owner and EHS Team approval are required for this HRA Hot Work permit</p>
                                        <p style="margin: 10px 0; font-size: 16px;">• Please review all safety checklist items and fire safety equipment before approving</p>
                                        <p style="margin-bottom: 0; font-size: 16px;">• Work cannot commence until both approvals are received</p>
                                    </td>
                                </tr>
                            </table>
                            
                            <!-- Action Section -->
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                <tr>
                                    <td class="action-section">
                                        <p style="margin-top: 0; font-size: 16px; color: #856404; font-weight: bold;">Click the button below to review and process this HRA approval:</p>
                                        <a href="{{ route('hra.hot-works.show', [$permit, $hraHotWork]) }}" class="action-button">
                                            🔗 Open HRA Hot Work - Review & Approve
                                        </a>
                                    </td>
                                </tr>
                            </table>
                            
                            <p style="font-size: 16px; margin: 20px 0;"><strong>To approve or reject this HRA:</strong><br>
                            1. Click the button above to open the HRA Hot Work page<br>
                            2. Review all safety requirements and checklist items<br>
                            3. Use the approval buttons on the web page based on your role</p>
                            
                            <p style="font-size: 16px; margin: 20px 0;">If you have any questions or concerns, please contact the requestor or EHS team immediately.</p>
                            
                            <p style="font-size: 16px; margin: 20px 0;">Best regards,<br>
                            <strong>PTW System</strong></p>
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td class="email-footer">
                            <p style="margin: 0; font-size: 14px;">This is an automated message from the Permit to Work System.</p>
                            <p style="margin: 5px 0 0 0; font-size: 14px;">Please do not reply directly to this email.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>