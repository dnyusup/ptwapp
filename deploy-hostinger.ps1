# Deploy script untuk Hostinger
Write-Host "Starting deployment to Hostinger..." -ForegroundColor Green

# 1. Upload semua file yang diperlukan
Write-Host "1. Checking required files..." -ForegroundColor Yellow

$requiredFiles = @(
    "ptw/app/Http/Controllers/HraLotoIsolationController.php",
    "ptw/app/Models/HraLotoIsolation.php",
    "ptw/resources/views/hra/loto-isolations/show.blade.php",
    "ptw/resources/views/hra/loto-isolations/edit.blade.php", 
    "ptw/resources/views/hra/loto-isolations/index.blade.php",
    "ptw/resources/views/hra/loto-isolations/pdf.blade.php",
    "ptw/routes/web.php"
)

foreach ($file in $requiredFiles) {
    if (Test-Path $file) {
        Write-Host "✓ $file exists" -ForegroundColor Green
    } else {
        Write-Host "✗ $file missing" -ForegroundColor Red
    }
}

# 2. Git operations
Write-Host "2. Git operations..." -ForegroundColor Yellow
git add .
git commit -m "Fix: Add complete HRA LOTO Isolation system for Hostinger deployment"
git push origin main

Write-Host "3. Manual steps needed on Hostinger:" -ForegroundColor Cyan
Write-Host "   - Pull latest changes: git pull origin main"
Write-Host "   - Clear route cache: php artisan route:clear"
Write-Host "   - Clear config cache: php artisan config:clear"
Write-Host "   - Clear view cache: php artisan view:clear"
Write-Host "   - Optimize autoloader: composer dump-autoload"
Write-Host "   - Cache routes: php artisan route:cache"

Write-Host "Deployment preparation complete!" -ForegroundColor Green