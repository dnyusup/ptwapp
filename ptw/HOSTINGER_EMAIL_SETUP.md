# ======================================
# KONFIGURASI SMTP HOSTINGER
# =========================================
# 
# Ganti konfigurasi MAIL di file .env dengan setting berikut:

MAIL_MAILER=smtp
MAIL_HOST=smtp.hostinger.com
MAIL_PORT=587
MAIL_USERNAME=your-email@yourdomain.com
MAIL_PASSWORD=your-email-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@yourdomain.com
MAIL_FROM_NAME="PTW Portal"

# ======================================
# PANDUAN SETUP HOSTINGER EMAIL
# ======================================

# 1. LOGIN KE HOSTINGER CPANEL
# - Masuk ke hpanel.hostinger.com
# - Login dengan akun Hostinger Anda

# 2. BUAT EMAIL ACCOUNT
# - Pilih menu "Email Accounts"
# - Klik "Create Email Account"
# - Masukkan nama email (contoh: noreply@yourdomain.com)
# - Set password yang kuat
# - Klik "Create"

# 3. SETTING SMTP CONFIGURATION
# SMTP Server: smtp.hostinger.com
# Port: 587 (TLS) atau 465 (SSL)
# Encryption: TLS atau SSL
# Username: email lengkap yang dibuat (noreply@yourdomain.com)
# Password: password email yang dibuat

# 4. CONTOH KONFIGURASI LENGKAP
# MAIL_MAILER=smtp
# MAIL_HOST=smtp.hostinger.com
# MAIL_PORT=587
# MAIL_USERNAME=noreply@contohperusahaan.com
# MAIL_PASSWORD=Password123!
# MAIL_ENCRYPTION=tls
# MAIL_FROM_ADDRESS=noreply@contohperusahaan.com
# MAIL_FROM_NAME="PTW Portal Perusahaan"

# 5. TESTING EMAIL
# Setelah setup, test dengan command:
# php artisan tinker
# Mail::raw('Test email', function($message) { $message->to('test@gmail.com')->subject('Test'); });

# ======================================
# TROUBLESHOOTING
# ======================================

# Jika email tidak terkirim, cek:
# 1. Pastikan email account sudah dibuat di Hostinger
# 2. Pastikan username = email lengkap
# 3. Pastikan password benar
# 4. Clear config cache: php artisan config:cache
# 5. Cek log Laravel: storage/logs/laravel.log

# ======================================
# SECURITY TIPS
# ======================================

# 1. Gunakan email khusus untuk system (noreply@domain.com)
# 2. Jangan gunakan password yang sama dengan account utama
# 3. Set password yang kuat (minimal 12 karakter)
# 4. Jangan commit file .env ke git (sudah ada di .gitignore)