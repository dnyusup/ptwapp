<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Permit Approval Result</title>
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
        
        /* Status styles */
        .status {
            font-weight: bold;
            color: {{ $result === 'approved' ? '#28a745' : '#dc3545' }};
            font-size: 18px;
            margin: 20px 0;
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
        
        /* Button styles */
        .btn-container {
            text-align: center;
            margin: 24px 0;
        }
        
        .btn-primary {
            background-color: #007bff;
            color: #ffffff;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            display: inline-block;
            border: none;
        }
        
        .btn-primary:hover {
            background-color: #0056b3;
            color: #ffffff;
            text-decoration: none;
        }
        
        /* Comment box */
        .comment-box {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            padding: 15px;
            margin: 20px 0;
        }
        
        .comment-label {
            font-weight: bold;
            color: #495057;
            margin-bottom: 8px;
        }
        
        /* Footer */
        .email-footer {
            font-size: 12px;
            color: #6c757d;
            text-align: center;
            margin-top: 30px;
            border-top: 1px solid #dee2e6;
            padding-top: 20px;
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
            
            .info-table {
                width: 100% !important;
            }
            
            .info-label,
            .info-value {
                display: block !important;
                width: 100% !important;
                padding: 8px 10px !important;
            }
            
            .info-label {
                border-bottom: none !important;
                padding-bottom: 4px !important;
            }
            
            .info-value {
                border-top: none !important;
                padding-top: 4px !important;
            }
        }
    </style>
</head>
<body>
    <!--[if mso | IE]>
    <table align="center" border="0" cellpadding="0" cellspacing="0" class="email-container-outlook" style="width:600px;" width="600">
        <tr>
            <td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;">
    <![endif]-->
    
    <div class="email-container">
        <!-- Header -->
        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
            <tr>
                <td class="email-header">
                    <h1>Permit Approval Notification</h1>
                </td>
            </tr>
        </table>
        
        <!-- Content -->
        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
            <tr>
                <td class="email-content">
                    <p>Dear {{ $permit->created_by_name ?? 'User' }},</p>
                    
                    <p>Your permit request <strong>{{ $permit->permit_number }}</strong> has been <span class="status">{{ strtoupper($result) }}</span>.</p>
                    
                    <!-- Info Table -->
                    <table class="info-table" role="presentation" cellspacing="0" cellpadding="0" border="0">
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
                            <td class="info-value">{{ \Carbon\Carbon::parse($permit->start_date)->format('d M Y') }} - {{ \Carbon\Carbon::parse($permit->end_date)->format('d M Y') }}</td>
                        </tr>
                        <tr>
                            <td class="info-label">Contractor:</td>
                            <td class="info-value">{{ $permit->receiver_company_name }}</td>
                        </tr>
                    </table>
                    
                    <!-- Button -->
                    <div class="btn-container">
                        <a href="{{ url('/permits/' . $permit->id) }}" class="btn-primary">
                            📋 View Permit Details
                        </a>
                    </div>
                    
                    @if($comment)
                    <div class="comment-box">
                        <div class="comment-label">Comment from EHS Team:</div>
                        {{ $comment }}
                    </div>
                    @endif
                    
                    <!-- Footer -->
                    <div class="email-footer">
                        This email was sent automatically from the PTW Portal System.<br>
                        Please do not reply to this email.
                    </div>
                </td>
            </tr>
        </table>
    </div>
    
    <!--[if mso | IE]>
            </td>
        </tr>
    </table>
    <![endif]-->
</body>
</html>
