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
            padding: 20px;
            text-align: center;
        }
        
        .email-header h1 {
            color: #ffffff !important;
            margin: 0;
            font-size: 24px;
            font-weight: bold;
        }
        
        /* Content styles */
        .email-content {
            padding: 30px;
        }
        
        .permit-info {
            background-color: #f8f9fa;
            border-left: 4px solid #dc3545;
            padding: 20px;
            margin: 20px 0;
        }
        
        .permit-info h3 {
            color: #dc3545;
            margin-top: 0;
            margin-bottom: 15px;
            font-size: 18px;
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
        
        .approval-buttons {
            text-align: center;
            margin: 30px 0;
        }
        
        .btn {
            display: inline-block;
            padding: 12px 24px;
            margin: 0 10px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            text-align: center;
            cursor: pointer;
        }
        
        .btn-approve {
            background-color: #28a745;
            color: #ffffff !important;
        }
        
        .btn-reject {
            background-color: #dc3545;
            color: #ffffff !important;
        }
        
        .btn:hover {
            opacity: 0.8;
        }
        
        .footer {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            color: #666666;
            font-size: 12px;
        }
        
        .important-note {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 5px;
            padding: 15px;
            margin: 20px 0;
        }
        
        .important-note h4 {
            color: #856404;
            margin-top: 0;
            margin-bottom: 10px;
        }
        
        .important-note p {
            color: #856404;
            margin: 0;
        }
        
        /* Responsive styles */
        @media screen and (max-width: 600px) {
            .email-container {
                width: 100% !important;
                max-width: 100% !important;
            }
            
            .email-content {
                padding: 20px !important;
            }
            
            .info-row {
                display: block !important;
            }
            
            .info-label, .info-value {
                display: block !important;
                width: 100% !important;
                padding-right: 0 !important;
            }
            
            .info-label {
                margin-bottom: 5px;
            }
            
            .info-value {
                margin-bottom: 15px;
            }
            
            .btn {
                display: block !important;
                margin: 10px 0 !important;
            }
        }
    </style>
</head>
<body>
    <!--[if mso | IE]>
    <table align="center" border="0" cellpadding="0" cellspacing="0" style="width:600px;" width="600">
        <tr>
            <td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;">
    <![endif]-->
    
    <div class="email-container">
        <!-- Header -->
        <div class="email-header">
            <h1>🔥 HRA Hot Work Approval Request</h1>
        </div>
        
        <!-- Content -->
        <div class="email-content">
            <p>Dear Area Owner & EHS Team,</p>
            
            <p>A new HRA Hot Work permit has been submitted and requires your approval. Please review the details below and take the necessary action.</p>
            
            <!-- HRA Information -->
            <div class="permit-info">
                <h3>📋 HRA Hot Work Details</h3>
                
                <div class="info-row">
                    <div class="info-label">HRA Number:</div>
                    <div class="info-value"><strong>{{ $hraHotWork->hra_permit_number }}</strong></div>
                </div>
                
                <div class="info-row">
                    <div class="info-label">Main Permit:</div>
                    <div class="info-value">{{ $permit->permit_number }}</div>
                </div>
                
                <div class="info-row">
                    <div class="info-label">Worker Name:</div>
                    <div class="info-value">{{ $hraHotWork->worker_name }}</div>
                </div>
                
                <div class="info-row">
                    <div class="info-label">Supervisor:</div>
                    <div class="info-value">{{ $hraHotWork->supervisor_name }}</div>
                </div>
                
                <div class="info-row">
                    <div class="info-label">Work Location:</div>
                    <div class="info-value">{{ $hraHotWork->work_location }}</div>
                </div>
                
                <div class="info-row">
                    <div class="info-label">Work Period:</div>
                    <div class="info-value">{{ $hraHotWork->start_datetime->format('d/m/Y H:i') }} - {{ $hraHotWork->end_datetime->format('d/m/Y H:i') }}</div>
                </div>
                
                <div class="info-row">
                    <div class="info-label">Work Description:</div>
                    <div class="info-value">{{ $hraHotWork->work_description }}</div>
                </div>
                
                <div class="info-row">
                    <div class="info-label">Requested by:</div>
                    <div class="info-value">{{ $permit->user->name ?? 'Unknown' }}</div>
                </div>
                
                <div class="info-row">
                    <div class="info-label">Request Date:</div>
                    <div class="info-value">{{ $hraHotWork->approval_requested_at->format('d/m/Y H:i') }}</div>
                </div>
            </div>
            
            <!-- Main Permit Information -->
            <div class="permit-info">
                <h3>🏢 Main Permit Information</h3>
                
                <div class="info-row">
                    <div class="info-label">Location Owner:</div>
                    <div class="info-value">{{ $permit->locationOwner ? $permit->locationOwner->name : 'Not specified' }}</div>
                </div>
                
                <div class="info-row">
                    <div class="info-label">Location Owner Email:</div>
                    <div class="info-value">{{ $permit->locationOwner ? $permit->locationOwner->email : 'Not specified' }}</div>
                </div>
                
                <div class="info-row">
                    <div class="info-label">EHS Contact:</div>
                    <div class="info-value">{{ $permit->ehs_email ?? 'Not specified' }}</div>
                </div>
            </div>
            
            <!-- Important Note -->
            <div class="important-note">
                <h4>⚠️ Important Notes:</h4>
                <p>• Both Location Owner and EHS Team approval are required for this HRA Hot Work permit</p>
                <p>• Please review all safety checklist items and fire safety equipment before approving</p>
                <p>• Work cannot commence until both approvals are received</p>
            </div>
            
            <!-- Action Button -->
            <div class="approval-buttons">
                <a href="{{ route('hra.hot-works.show', [$permit, $hraHotWork]) }}" class="btn btn-approve" style="background-color: #28a745; padding: 12px 24px; text-decoration: none; color: white; border-radius: 4px; font-weight: bold; display: inline-block; margin: 10px 0;">
                    🔗 Open HRA Hot Work - Review & Approve
                </a>
            </div>
            
            <p><strong>To approve or reject this HRA:</strong><br>
            1. Click the button above to open the HRA Hot Work page<br>
            2. Review all safety requirements and checklist items<br>
            3. Use the approval buttons on the web page based on your role</p>
            
            <p>If you have any questions or concerns, please contact the requestor or EHS team immediately.</p>
            
            <p>Best regards,<br>
            <strong>PTW System</strong></p>
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <p>This is an automated message from the Permit to Work System.</p>
            <p>Please do not reply directly to this email.</p>
        </div>
    </div>
    
    <!--[if mso | IE]>
            </td>
        </tr>
    </table>
    <![endif]-->
</body>
</html>