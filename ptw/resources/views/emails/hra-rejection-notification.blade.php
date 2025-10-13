<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HRA Hot Work Rejected</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 16px;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 0;
        }
        .email-header {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .email-header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: bold;
        }
        .email-header .subtitle {
            margin: 10px 0 0 0;
            font-size: 16px;
            opacity: 0.9;
        }
        .email-body {
            padding: 30px;
        }
        .greeting {
            font-size: 18px;
            color: #333;
            margin-bottom: 20px;
        }
        .message {
            font-size: 16px;
            color: #555;
            margin-bottom: 25px;
            line-height: 1.7;
        }
        .rejection-box {
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            border-radius: 8px;
            padding: 20px;
            margin: 25px 0;
        }
        .rejection-title {
            font-size: 18px;
            font-weight: bold;
            color: #721c24;
            margin-bottom: 10px;
        }
        .rejection-reason {
            background-color: #ffffff;
            border: 1px solid #f5c6cb;
            border-radius: 4px;
            padding: 15px;
            margin-top: 10px;
            font-style: italic;
            color: #721c24;
        }
        .details-section {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 25px;
            margin: 25px 0;
        }
        .details-title {
            font-size: 20px;
            font-weight: bold;
            color: #dc3545;
            margin-bottom: 20px;
            border-bottom: 2px solid #dc3545;
            padding-bottom: 10px;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .info-table td {
            padding: 12px 0;
            border-bottom: 1px solid #dee2e6;
            vertical-align: top;
        }
        .info-table td:first-child {
            font-weight: bold;
            color: #495057;
            width: 35%;
        }
        .info-table td:last-child {
            color: #212529;
        }
        .action-section {
            text-align: center;
            margin: 30px 0;
        }
        .btn {
            display: inline-block;
            padding: 15px 30px;
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            font-size: 16px;
            margin: 10px;
            box-shadow: 0 4px 8px rgba(0,123,255,0.3);
            transition: all 0.3s ease;
        }
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0,123,255,0.4);
        }
        .next-steps {
            background-color: #e7f3ff;
            border: 1px solid #b3d7ff;
            border-radius: 8px;
            padding: 20px;
            margin: 25px 0;
        }
        .next-steps-title {
            font-size: 18px;
            font-weight: bold;
            color: #0056b3;
            margin-bottom: 15px;
        }
        .next-steps ul {
            margin: 0;
            padding-left: 20px;
            color: #495057;
        }
        .next-steps li {
            margin-bottom: 8px;
        }
        .email-footer {
            background-color: #f8f9fa;
            padding: 25px;
            text-align: center;
            border-top: 1px solid #dee2e6;
        }
        .footer-text {
            color: #6c757d;
            font-size: 14px;
            margin: 0;
        }
        .company-info {
            margin-top: 15px;
            color: #6c757d;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="email-header">
            <h1>🚫 HRA Hot Work Rejected</h1>
            <div class="subtitle">{{ $hraHotWork->hra_permit_number }}</div>
        </div>

        <!-- Body -->
        <div class="email-body">
            <div class="greeting">Dear {{ $hraHotWork->user->name }},</div>
            
            <div class="message">
                Your HRA Hot Work permit has been <strong>rejected</strong> and requires revision before it can be approved. 
                Please review the rejection reason below and make the necessary corrections.
            </div>

            <!-- Rejection Details -->
            <div class="rejection-box">
                <div class="rejection-title">❌ Rejection Details</div>
                <table class="info-table">
                    <tr>
                        <td>Rejected By:</td>
                        <td><strong>{{ $rejectedBy }}</strong></td>
                    </tr>
                    <tr>
                        <td>Rejection Date:</td>
                        <td>{{ now()->format('d M Y, H:i') }}</td>
                    </tr>
                </table>
                <div class="rejection-reason">
                    <strong>Reason for Rejection:</strong><br>
                    {{ $rejectionReason }}
                </div>
            </div>

            <!-- HRA Details -->
            <div class="details-section">
                <div class="details-title">📋 HRA Hot Work Details</div>
                <table class="info-table">
                    <tr>
                        <td>HRA Number:</td>
                        <td><strong>{{ $hraHotWork->hra_permit_number }}</strong></td>
                    </tr>
                    <tr>
                        <td>Main Permit:</td>
                        <td>{{ $permit->permit_number }}</td>
                    </tr>
                    <tr>
                        <td>Work Title:</td>
                        <td>{{ $permit->work_title }}</td>
                    </tr>
                    <tr>
                        <td>Work Location:</td>
                        <td>{{ $permit->work_location }}</td>
                    </tr>
                    <tr>
                        <td>Worker Name:</td>
                        <td>{{ $hraHotWork->worker_name }}</td>
                    </tr>
                    <tr>
                        <td>Work Period:</td>
                        <td>{{ $hraHotWork->start_datetime->format('d M Y, H:i') }} to {{ $hraHotWork->end_datetime->format('d M Y, H:i') }}</td>
                    </tr>
                </table>
            </div>

            <!-- Next Steps -->
            <div class="next-steps">
                <div class="next-steps-title">📝 Next Steps</div>
                <ul>
                    <li>Review the rejection reason carefully</li>
                    <li>Edit your HRA Hot Work to address the issues mentioned</li>
                    <li>Resubmit the HRA for approval once corrections are made</li>
                    <li>Contact the Location Owner or EHS Team if you need clarification</li>
                </ul>
            </div>

            <!-- Action Button -->
            <div class="action-section">
                <a href="{{ route('hra.hot-works.show', [$permit, $hraHotWork]) }}" class="btn">
                    🔧 Edit HRA Hot Work
                </a>
            </div>

            <!-- Additional Info -->
            <div class="message">
                <strong>Important:</strong> After making the necessary corrections, you can resubmit your HRA Hot Work for approval. 
                Both Location Owner and EHS Team approval are required for this permit.
            </div>
        </div>

        <!-- Footer -->
        <div class="email-footer">
            <p class="footer-text">
                This is an automated notification from the PTW Portal System.<br>
                Please do not reply directly to this email.
            </p>
            <div class="company-info">
                <strong>PTW Portal System</strong><br>
                Permit to Work Management System
            </div>
        </div>
    </div>
</body>
</html>