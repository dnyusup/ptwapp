# Alternative SMTP Configuration Options

## Option 1: Gmail SMTP (For Testing)
```
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-gmail@gmail.com
MAIL_PASSWORD=your-app-password  # Not regular password!
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-gmail@gmail.com
MAIL_FROM_NAME="PTW Portal"
```

Note: Gmail requires "App Password" not regular password
1. Enable 2-step verification
2. Generate App Password in Google Account settings
3. Use App Password in MAIL_PASSWORD

## Option 2: Mailtrap (Development Testing)
```
MAIL_MAILER=smtp
MAIL_HOST=live.smtp.mailtrap.io
MAIL_PORT=587
MAIL_USERNAME=api
MAIL_PASSWORD=your-mailtrap-token
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=hello@demomailtrap.com
MAIL_FROM_NAME="PTW Portal"
```

## Option 3: Reset Hostinger Email Account
1. Go to Hostinger hPanel
2. Email Accounts → Manage → Reset Password
3. Set new strong password (no special characters that might cause issues)
4. Make sure SMTP is enabled for the account

## Current Hostinger Issue Analysis:
✅ SMTP server connection successful (smtp.hostinger.com:465)
✅ SSL connection established 
✅ Server accepts AUTH LOGIN
❌ Credentials rejected (535 authentication failed)

This suggests either:
- Wrong password (despite showing correct in panel)
- Account restrictions preventing SMTP access
- Need to enable SMTP in email account settings