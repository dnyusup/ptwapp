<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HRA {{ $hraType ?? 'Hot Work' }} Approved</title>
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
            background-color: #28a745;
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
            padding: 8px 16px;
            border-radius: 4px;
            font-weight: bold;
            display: inline-block;
            margin: 10px 0;
        }
        
        /* Button styles */
        .btn-view {
            background-color: #007bff;
            color: #ffffff !important;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
            display: inline-block;
            margin: 15px 0;
        }
        
        /* Section styles */
        .section-title {
            background-color: #007bff;
            color: #ffffff;
            padding: 10px 15px;
            margin: 0;
            font-size: 16px;
            font-weight: bold;
        }
        
        .section-content {
            padding: 15px;
            border: 1px solid #dee2e6;
            border-top: none;
        }
        
        /* Footer */
        .email-footer {
            background-color: #6c757d;
            color: #ffffff;
            padding: 20px;
            text-align: center;
            font-size: 12px;
        }
        
        /* Important notice */
        .notice {
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
            padding: 15px;
            border-radius: 4px;
            margin: 20px 0;
        }
        
        /* Responsive styles */
        @media only screen and (max-width: 600px) {
            .email-container {
                width: 100% !important;
                max-width: 100% !important;
            }
            
            .email-content {
                padding: 20px !important;
            }
            
            .info-label,
            .info-value {
                display: block !important;
                width: 100% !important;
            }
            
            .info-label {
                padding-bottom: 5px !important;
            }
            
            .info-value {
                padding-top: 5px !important;
            }
        }
    </style>
</head>
<body>
    <!--[if mso | IE]>
    <table align="center" border="0" cellpadding="0" cellspacing="0" class="email-container" style="width:600px;" width="600">
        <tr>
            <td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;">
    <![endif]-->
    
    <table class="email-container" align="center" border="0" cellpadding="0" cellspacing="0" style="width:100%;max-width:600px;">
        <!-- Header -->
        <tr>
            <td class="email-header">
                <h1>✅ HRA {{ $hraType ?? 'Hot Work' }} Approved</h1>
            </td>
        </tr>
        
        <!-- Content -->
        <tr>
            <td class="email-content">
                <p style="font-size: 16px; margin-bottom: 20px;">Dear HRA Creator,</p>
                
                <p>Good news! Your HRA {{ $hraType ?? 'Hot Work' }} permit has been <strong>approved</strong> and is now ready for work execution.</p>
                
                <div class="notice">
                    <strong>🎉 Approval Completed!</strong><br>
                    EHS Team has approved this HRA {{ $hraType ?? 'Hot Work' }} permit. You may now proceed with the work activities as outlined in the permit.
                </div>
                
                <!-- HRA Details Section -->
                <table class="info-table">
                    <tr>
                        <td colspan="2">
                            <div class="section-title">📋 HRA {{ $hraType ?? 'Hot Work' }} Details</div>
                        </td>
                    </tr>
                    <tr>
                        <td class="info-label">HRA Number:</td>
                        <td class="info-value">{{ $hra->hra_permit_number }}</td>
                    </tr>
                    <tr>
                        <td class="info-label">Main Permit:</td>
                        <td class="info-value">{{ $permit->permit_number }}</td>
                    </tr>
                    <tr>
                        <td class="info-label">Work Title:</td>
                        <td class="info-value">{{ $permit->work_title }}</td>
                    </tr>
                    <tr>
                        <td class="info-label">Work Location:</td>
                        <td class="info-value">{{ $hra->work_location }}</td>
                    </tr>
                    <tr>
                        <td class="info-label">Worker Name:</td>
                        <td class="info-value">{{ $hra->worker_name }}</td>
                    </tr>
                    <tr>
                        <td class="info-label">Work Period:</td>
                        <td class="info-value">
                            {{ $hra->start_datetime ? $hra->start_datetime->format('d M Y H:i') : 'Not specified' }}
                            @if($hra->end_datetime)
                                <br>to {{ $hra->end_datetime->format('d M Y H:i') }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="info-label">Status:</td>
                        <td class="info-value">
                            <span class="status-badge">✅ APPROVED</span>
                        </td>
                    </tr>
                </table>
                
                <!-- Approval Details Section -->
                <table class="info-table">
                    <tr>
                        <td colspan="2">
                            <div class="section-title">👥 Approval Details</div>
                        </td>
                    </tr>
                    <tr>
                        <td class="info-label">Location Owner:</td>
                        <td class="info-value">
                            {{ $permit->locationOwner ? $permit->locationOwner->name : 'Not specified' }}
                            <br><small style="color: #6c757d;">(CC only)</small>
                        </td>
                    </tr>
                    <tr>
                        <td class="info-label">EHS Team:</td>
                        <td class="info-value">
                            EHS Department
                            @if($hra->ehs_approval === 'approved')
                                <br><span style="color: #28a745; font-weight: bold;">✅ Approved</span>
                                @if($hra->ehs_approved_at)
                                    <br><small>{{ $hra->ehs_approved_at->format('d M Y H:i') }}</small>
                                @endif
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="info-label">Final Approval:</td>
                        <td class="info-value">
                            <span style="color: #28a745; font-weight: bold;">✅ Approved</span>
                            @if($hra->final_approved_at)
                                <br><small>{{ $hra->final_approved_at->format('d M Y H:i') }}</small>
                            @endif
                        </td>
                    </tr>
                </table>
                
                <!-- Action Button -->
                <div style="text-align: center; margin: 30px 0;">
                    @if(($hraType ?? 'Hot Work') === 'LOTO/Isolation')
                    <a href="{{ route('hra.loto-isolations.show', [$permit, $hra]) }}" class="btn-view">
                        🔍 View HRA LOTO/Isolation Details
                    </a>
                    @else
                    <a href="{{ route('hra.hot-works.show', [$permit, $hra]) }}" class="btn-view">
                        🔍 View HRA {{ $hraType ?? 'Hot Work' }} Details
                    </a>
                    @endif
                </div>
                
                <!-- Important Notes -->
                <div style="background-color: #fff3cd; border: 1px solid #ffeaa7; color: #856404; padding: 15px; border-radius: 4px; margin: 20px 0;">
                    <h4 style="margin: 0 0 10px 0; color: #856404;">⚠️ Important Reminders:</h4>
                    <ul style="margin: 0; padding-left: 20px;">
                        <li>Ensure all safety requirements outlined in the HRA are followed during work execution</li>
                        @if(($hraType ?? 'Hot Work') === 'Hot Work')
                        <li>Keep fire watch personnel and firefighting equipment readily available</li>
                        <li>Monitor gas levels if gas monitoring is required</li>
                        @elseif(($hraType ?? 'Hot Work') === 'LOTO/Isolation')
                        <li>Ensure all isolation points are properly locked and tagged</li>
                        <li>Verify zero energy state before starting work</li>
                        @endif
                        <li>Stop work immediately if conditions change or safety concerns arise</li>
                        <li>Complete work within the approved time period</li>
                    </ul>
                </div>
                
                <p>If you have any questions or need assistance, please contact the Safety Department.</p>
                
                <p style="margin-top: 30px;">
                    Best regards,<br>
                    <strong>PTW System</strong><br>
                    Safety Management Team
                </p>
            </td>
        </tr>
        
        <!-- Footer -->
        <tr>
            <td class="email-footer">
                <p style="margin: 0;">This is an automated notification from the Permit to Work System.</p>
                <p style="margin: 5px 0 0 0;">Please do not reply to this email.</p>
            </td>
        </tr>
    </table>
    
    <!--[if mso | IE]>
            </td>
        </tr>
    </table>
    <![endif]-->
</body>
</html>