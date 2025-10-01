# SMTP Provider Switching Guide

## Switching SMTP providers hanya perlu ubah .env file

### Gmail SMTP (untuk testing sekarang):
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-gmail@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-gmail@gmail.com
MAIL_FROM_NAME="PTW Portal"
```

### Hostinger SMTP (untuk production nanti):
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.hostinger.com
MAIL_PORT=587
MAIL_USERNAME=no-reply@ptw.aplikasibebas.com
MAIL_PASSWORD=ptw2025
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=no-reply@ptw.aplikasibebas.com
MAIL_FROM_NAME="PTW Portal"
```

## Workflow yang disarankan:

### 1. Development (Local) - Gunakan Gmail
- Test email notification system
- Pastikan semua fitur bekerja
- Debug dan develop dengan aman

### 2. Production (Hostinger) - Gunakan Hostinger SMTP
- Deploy ke server Hostinger
- Ubah .env ke Hostinger SMTP
- Email akan dikirim dari domain asli

## Keuntungan pendekatan ini:

✅ **Tidak ada perubahan code** - hanya konfigurasi .env
✅ **Email system tetap sama** - Mailable class, template, controller logic tidak berubah
✅ **Easy switching** - tinggal copy-paste konfigurasi .env
✅ **Testing aman** - tidak spam real users saat development

## Files yang TIDAK perlu diubah:
- app/Mail/PermitApprovalRequest.php ✅
- app/Http/Controllers/PermitToWorkController.php ✅
- resources/views/emails/permit-approval-request.blade.php ✅
- routes/web.php ✅

## Yang berubah hanya:
- .env file (6 baris konfigurasi saja)