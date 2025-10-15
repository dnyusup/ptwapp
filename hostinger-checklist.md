# CHECKLIST FILE UNTUK HOSTINGER

## File Wajib Upload:

### 1. Controller (PALING PENTING!)
📁 app/Http/Controllers/HraLotoIsolationController.php
   - Tanpa ini semua route akan 404
   - Size: ~15KB

### 2. Model  
📁 app/Models/HraLotoIsolation.php
   - Size: ~3KB

### 3. Views (semua file)
📁 resources/views/hra/loto-isolations/
   ├── show.blade.php (~25KB)
   ├── edit.blade.php (~30KB) 
   ├── index.blade.php (~8KB)
   └── pdf.blade.php (~20KB)

### 4. Routes
📁 routes/web.php
   - Update dengan loto-isolations routes
   - Size: ~5KB

## Perintah di Terminal Hostinger SETELAH Upload:

1. composer dump-autoload
2. php artisan route:clear
3. php artisan config:clear  
4. php artisan view:clear
5. php artisan route:cache

## Test:
php artisan route:list --name=loto-isolations

Jika masih 404, berarti ada file yang belum terupload dengan benar.