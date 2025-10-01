<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Permit Approval Result</title>
    <style>
        body { font-family: Arial, Helvetica, sans-serif; background: #f4f4f4; color: #333; }
        .container { max-width: 600px; margin: 0 auto; background: #fff; padding: 30px; border-radius: 8px; }
        .header { background: #007bff; color: #fff; padding: 20px; text-align: center; border-radius: 8px 8px 0 0; }
        .status {
            font-weight: bold;
            color: {{ $result === 'approved' ? '#28a745' : '#dc3545' }};
            font-size: 18px;
            margin: 20px 0;
        }
        .info-table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        .info-table td { padding: 8px 10px; border-bottom: 1px solid #eee; }
        .info-label { font-weight: bold; background: #f8f9fa; width: 35%; }
        .info-value { width: 65%; }
        .comment-box { background: #f8f9fa; border: 1px solid #dee2e6; border-radius: 4px; padding: 12px; margin: 10px 0; }
        .footer { font-size: 12px; color: #888; text-align: center; margin-top: 30px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Permit Approval Notification</h2>
        </div>
        <p>Dear {{ $permit->created_by_name ?? 'User' }},</p>
        <p>Your permit request <strong>{{ $permit->permit_number }}</strong> has been <span class="status">{{ strtoupper($result) }}</span>.</p>
        <table class="info-table">
            <tr><td class="info-label">Work Title:</td><td class="info-value">{{ $permit->work_title }}</td></tr>
            <tr><td class="info-label">Location:</td><td class="info-value">{{ $permit->work_location }}</td></tr>
            <tr><td class="info-label">Work Period:</td><td class="info-value">{{ \Carbon\Carbon::parse($permit->work_start_date)->format('d M Y') }} - {{ \Carbon\Carbon::parse($permit->work_end_date)->format('d M Y') }}</td></tr>
            <tr><td class="info-label">Contractor:</td><td class="info-value">{{ $permit->receiver_company_name }}</td></tr>
        </table>
        <!-- Tombol View Permit Details -->
        <div style="text-align:center; margin: 24px 0;">
            <a href="{{ url('/permits/' . $permit->id) }}" style="background-color: #007bff; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px; font-weight: bold; display: inline-block;">
                📋 View Permit Details
            </a>
        </div>
        @if($comment)
        <div class="comment-box">
            <strong>Comment from EHS Team:</strong><br>
            {{ $comment }}
        </div>
        @endif
        <div class="footer">
            This email was sent automatically from the PTW Portal System.<br>
            Please do not reply to this email.
        </div>
    </div>
</body>
</html>
