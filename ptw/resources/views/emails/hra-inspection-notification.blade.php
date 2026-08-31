<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HRA Hot Work Inspection</title>
    <style>
        body { margin:0; padding:0; background:#f4f4f4; font-family:Arial,Helvetica,sans-serif; font-size:14px; line-height:1.6; color:#333; }
        .email-container { max-width:600px; margin:0 auto; background:#ffffff; }
        .email-header { background:#dc3545; color:#fff; padding:20px; text-align:center; }
        .email-header h1 { margin:0; font-size:22px; }
        .section-header { background:#dc3545; color:#fff; padding:10px 15px; font-weight:bold; margin:20px 0 0; }
        .inspection-section { background:#fff3cd; color:#856404; padding:10px 15px; font-weight:bold; margin:20px 0 0; }
        .info-table { width:100%; border-collapse:collapse; margin:0 0 10px; background:#f8f9fa; border:1px solid #dee2e6; }
        .info-table td { padding:12px 15px; border-bottom:1px solid #dee2e6; vertical-align:top; }
        .info-label { font-weight:bold; width:35%; color:#495057; background:#e9ecef; }
        .description-box { background:#f8f9fa; padding:15px; border:1px solid #dee2e6; border-radius:4px; margin:10px 0; white-space:pre-wrap; }
        .email-footer { background:#f8f9fa; padding:20px; text-align:center; border-top:1px solid #dee2e6; font-size:12px; color:#6c757d; }
    </style>
</head>
<body>
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background:#f4f4f4;">
        <tr>
            <td align="center" style="padding:20px 0;">
                <table class="email-container" role="presentation" cellspacing="0" cellpadding="0" border="0">
                    <tr><td class="email-header"><h1>🔍 HRA Hot Work Inspection #{{ $inspection->sequence }}</h1></td></tr>
                    <tr>
                        <td style="padding:30px;">
                            <p>Dear EHS Team,</p>
                            <p>Inspection <strong>#{{ $inspection->sequence }} of {{ $required }}</strong> has been recorded for an approved HRA Hot Work.</p>

                            <div class="section-header">📋 Permit Details</div>
                            <table class="info-table" role="presentation" cellspacing="0" cellpadding="0" border="0">
                                <tr><td class="info-label">Main Permit:</td><td><strong>{{ $permit->permit_number ?? '-' }}</strong></td></tr>
                                <tr><td class="info-label">HRA Number:</td><td>{{ $hra->hra_permit_number ?? '-' }}</td></tr>
                                <tr><td class="info-label">Work Title:</td><td>{{ $permit->work_title ?? '-' }}</td></tr>
                                <tr><td class="info-label">Location:</td><td>{{ $hra->work_location ?: ($permit->work_location ?? '-') }}</td></tr>
                            </table>

                            <div class="inspection-section">🔍 Inspection #{{ $inspection->sequence }} Details</div>
                            <table class="info-table" role="presentation" cellspacing="0" cellpadding="0" border="0">
                                <tr><td class="info-label">Inspector:</td><td><strong>{{ $inspection->inspector_name }}</strong></td></tr>
                                <tr><td class="info-label">Inspector Email:</td><td>{{ $inspection->inspector_email }}</td></tr>
                                <tr><td class="info-label">Inspection Time:</td><td>{{ optional($inspection->inspected_at)->format('d M Y H:i') }}</td></tr>
                                <tr>
                                    <td class="info-label">Finding Type:</td>
                                    <td>
                                        <span style="font-weight:bold; color:{{ $inspection->finding_type === 'OK' ? '#28a745' : '#dc3545' }};">
                                            {{ $inspection->finding_type }}
                                        </span>
                                    </td>
                                </tr>
                            </table>

                            @if($inspection->findings)
                            <div class="inspection-section">📝 Findings</div>
                            <div class="description-box">{{ $inspection->findings }}</div>
                            @endif

                            @if(isset($hasPhoto) && $hasPhoto)
                            <div class="inspection-section">📷 Foto Inspeksi</div>
                            <div style="background:#f8f9fa; padding:15px; border:1px solid #dee2e6; border-radius:4px; margin:10px 0; text-align:center;">
                                <img src="{{ $message->embed(storage_path('app/public/' . $inspection->photo_path)) }}"
                                     alt="Foto Inspeksi" width="300"
                                     style="width:300px; height:auto; border-radius:4px; border:1px solid #dee2e6;">
                            </div>
                            @endif

                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" style="margin:24px auto;">
                                <tr><td style="text-align:center;">
                                    <a href="{{ url('/permits/' . ($permit->id ?? '') . '/hra/hot-works/' . $hra->id) }}"
                                       style="background:#dc3545; color:#fff; padding:12px 24px; text-decoration:none; border-radius:5px; font-weight:bold; display:inline-block;">
                                        🔍 View HRA Hot Work
                                    </a>
                                </td></tr>
                            </table>
                        </td>
                    </tr>
                    <tr><td class="email-footer">
                        <p>This email was sent automatically from the PTW Portal System. Please do not reply.</p>
                    </td></tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
