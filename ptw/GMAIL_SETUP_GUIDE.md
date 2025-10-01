# Gmail App Password Setup Guide

## Link untuk generate App Password:
**https://myaccount.google.com/apppasswords**

## Step-by-step Setup:

### 1. Enable 2-Step Verification (wajib):
- Go to: https://myaccount.google.com/security
- Klik "2-Step Verification" 
- Follow setup process (butuh nomor HP)

### 2. Generate App Password:
- Go to: https://myaccount.google.com/apppasswords
- Select app: "Mail"
- Select device: "Other (custom name)" → type "Laravel PTW"
- Klik "Generate"
- Copy password 16 digit (format: xxxx xxxx xxxx xxxx)

### 3. Update .env file:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-16-digit-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
MAIL_FROM_NAME="PTW Portal"
```

## Alternative Method (jika link tidak bekerja):

### Via Google Account Settings:
1. https://myaccount.google.com/
2. Security → 2-Step Verification (enable first)
3. Security → App passwords
4. Select "Mail" and "Other"
5. Generate password

### Direct Search:
- Search "Google App Password" di Google
- Atau search "Gmail SMTP App Password"

## Notes:
- **WAJIB** enable 2-step verification dulu
- App password berbeda dengan password Gmail biasa
- Format: 16 karakter tanpa spasi saat input ke .env
- Satu app password bisa dipakai untuk beberapa aplikasi

## Test setelah setup:
```bash
php artisan config:clear
php artisan test:email-approval 16 deni.yusup@bekaert.com
```