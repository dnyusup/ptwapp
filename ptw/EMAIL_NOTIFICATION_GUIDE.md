# 📧 Email Notification System - Permit Approval

## 🎯 Fitur yang Telah Diimplementasikan

✅ **Email Notification**: Otomatis kirim email ke team EHS saat ada request approval
✅ **Professional Template**: Email dengan design yang menarik dan informatif  
✅ **Security Check**: Hanya pembuat permit yang bisa request approval
✅ **Error Handling**: Rollback status jika email gagal terkirim
✅ **Testing Command**: Command untuk test email secara manual

## 🔧 Setup SMTP Hostinger

### 1. Buat Email Account di Hostinger
1. Login ke **hpanel.hostinger.com** 
2. Masuk ke **Email Accounts**
3. Klik **Create Email Account**
4. Buat email untuk sistem (contoh: `noreply@yourdomain.com`)
5. Set password yang kuat
6. Klik **Create**

### 2. Update File .env
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.hostinger.com
MAIL_PORT=587
MAIL_USERNAME=noreply@yourdomain.com
MAIL_PASSWORD=your-strong-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="PTW Portal"
```

### 3. Clear Cache
```bash
php artisan config:cache
php artisan route:cache
```

## 🚀 Cara Menggunakan

### 1. Request Approval dari UI
1. Buka halaman detail permit
2. Pastikan Method Statement sudah **Completed**
3. Klik tombol **Request Approval**
4. Konfirmasi pada dialog popup
5. System otomatis:
   - Update status permit ke **Pending Approval**
   - Kirim email ke semua user EHS
   - Tampilkan success message

### 2. Testing Email Manual
```bash
# Test dengan permit ID 1 ke email test@gmail.com
php artisan test:email-approval 1 test@gmail.com
```

## 📧 Email Template Features

### Header Section
- Subject: "Permit Approval Request - PTW-2025-XXX"
- Professional header dengan gradient background
- Emoji dan icon yang menarik

### Permit Details
- Permit Number
- Work Title & Description  
- Location & Building/Floor
- Work Period (Start - End Date)
- Responsible Person & Company
- Equipment & Tools
- Status Badge

### Call to Action
- Button "View & Approve Permit"
- Link langsung ke halaman permit detail
- Hover effect yang smooth

### Footer
- Company branding
- Auto-generated timestamp
- Professional disclaimer

## 🔒 Security Features

### Authorization Checks
- ✅ Hanya pembuat permit yang bisa request approval
- ✅ Method statement harus completed
- ✅ Permit harus dalam status yang valid
- ✅ User authentication required

### Error Handling  
- ✅ Rollback status jika email gagal
- ✅ Informative error messages
- ✅ Exception handling untuk SMTP issues
- ✅ Validation di frontend dan backend

## 📊 Database Changes

### Routes Added
```php
Route::post('/permits/{permit}/request-approval', [PermitToWorkController::class, 'requestApproval']);
```

### Controller Method
- `requestApproval(PermitToWork $permit)` di `PermitToWorkController`
- Complete validation dan email sending logic
- Error handling dengan rollback mechanism

### View Changes
- Updated tombol Request Approval di `permits/show.blade.php`
- Improved confirmation message
- Better UI feedback

## 🧪 Testing Scenarios

### 1. Positive Test
- ✅ Permit dengan method statement completed
- ✅ User adalah pembuat permit  
- ✅ Status permit valid untuk approval
- ✅ Ada user EHS di database
- **Expected**: Email terkirim, status berubah ke pending_approval

### 2. Negative Tests
- ❌ User bukan pembuat permit → Access Denied
- ❌ Method statement belum completed → Error message
- ❌ Status permit tidak valid → Error message  
- ❌ Tidak ada user EHS → Warning message
- ❌ SMTP error → Rollback status + error message

## 📝 Email Content Preview

```
Subject: Permit Approval Request - PTW-2025-0004

🔔 Permit Approval Request
A new permit requires your approval

Dear EHS Team,

A new permit has been submitted and requires your approval. 
Please review the details below:

📋 Permit Details
Permit Number: PTW-2025-0004
Work Title: Maintenance Electrical Panel  
Location: Building A - Floor 2
Work Period: 15 Sep 2025 - 16 Sep 2025
Responsible Person: John Doe
Company: PT Contractor ABC
Status: Pending Approval

📝 Work Description:
Routine maintenance and inspection of main electrical panel...

🔧 Equipment & Tools:
Multimeter, Insulation tester, Hand tools...

[👀 View & Approve Permit]

⏰ Action Required: Please review and approve this permit 
as soon as possible to avoid work delays.
```

## 🔍 Troubleshooting

### Email Tidak Terkirim
1. Cek konfigurasi SMTP di `.env`
2. Pastikan email account sudah dibuat di Hostinger
3. Test koneksi SMTP manual
4. Cek Laravel log: `storage/logs/laravel.log`
5. Pastikan port 587 tidak diblock firewall

### Button Tidak Muncul
1. Cek apakah user adalah pembuat permit
2. Pastikan method statement sudah completed  
3. Cek status permit (harus draft/rejected/resubmitted)
4. Verify method `canRequestApproval()` di model

### Error 500
1. Cek Laravel log untuk detail error
2. Pastikan semua dependencies ter-install
3. Clear cache: `php artisan cache:clear`
4. Check database connection

## 📈 Next Improvements

### Possible Enhancements
- [ ] Email template customization per company
- [ ] Email tracking (read receipts)
- [ ] Multiple approval levels
- [ ] Email scheduling/queuing
- [ ] SMS notification backup
- [ ] Email templates dalam Bahasa Indonesia
- [ ] Attachment PDF permit ke email
- [ ] Email reminder untuk pending approvals

### Performance Optimization
- [ ] Queue email sending untuk multiple recipients
- [ ] Batch email processing
- [ ] Email template caching
- [ ] SMTP connection pooling

## 💡 Tips & Best Practices

1. **Email Account**: Gunakan email khusus sistem (noreply@domain.com)
2. **Password**: Gunakan password yang kuat dan unique
3. **Testing**: Selalu test email setelah perubahan konfigurasi
4. **Monitoring**: Monitor email delivery rate dan error logs
5. **Backup**: Setup backup SMTP provider jika diperlukan
6. **Security**: Jangan commit credentials ke git
7. **Performance**: Pertimbangkan queue untuk high-volume emails

---

*Email notification system ready to use! 🎉*