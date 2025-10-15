<?php
// Debug script untuk cek kenapa route 404 di Hostinger
// Simpan sebagai debug-loto.php di root directory Hostinger

echo "<h2>Debug HRA LOTO Routes - Hostinger</h2>";

// 1. Cek apakah controller ada
$controllerPath = __DIR__ . '/app/Http/Controllers/HraLotoIsolationController.php';
echo "<h3>1. Controller Check:</h3>";
if (file_exists($controllerPath)) {
    echo "✅ HraLotoIsolationController.php EXISTS<br>";
    echo "Path: " . $controllerPath . "<br>";
    echo "Size: " . filesize($controllerPath) . " bytes<br>";
} else {
    echo "❌ HraLotoIsolationController.php MISSING<br>";
    echo "Expected path: " . $controllerPath . "<br>";
}

// 2. Cek apakah model ada
$modelPath = __DIR__ . '/app/Models/HraLotoIsolation.php';
echo "<h3>2. Model Check:</h3>";
if (file_exists($modelPath)) {
    echo "✅ HraLotoIsolation.php EXISTS<br>";
    echo "Path: " . $modelPath . "<br>";
    echo "Size: " . filesize($modelPath) . " bytes<br>";
} else {
    echo "❌ HraLotoIsolation.php MISSING<br>";
    echo "Expected path: " . $modelPath . "<br>";
}

// 3. Cek apakah view folder ada
$viewPath = __DIR__ . '/resources/views/hra/loto-isolations';
echo "<h3>3. Views Check:</h3>";
if (is_dir($viewPath)) {
    echo "✅ loto-isolations folder EXISTS<br>";
    echo "Path: " . $viewPath . "<br>";
    
    $files = scandir($viewPath);
    echo "Files in folder:<br>";
    foreach ($files as $file) {
        if ($file != '.' && $file != '..') {
            echo "- " . $file . " (" . filesize($viewPath . '/' . $file) . " bytes)<br>";
        }
    }
} else {
    echo "❌ loto-isolations folder MISSING<br>";
    echo "Expected path: " . $viewPath . "<br>";
}

// 4. Cek routes file
$routesPath = __DIR__ . '/routes/web.php';
echo "<h3>4. Routes Check:</h3>";
if (file_exists($routesPath)) {
    echo "✅ web.php EXISTS<br>";
    $content = file_get_contents($routesPath);
    if (strpos($content, 'loto-isolations') !== false) {
        echo "✅ loto-isolations routes FOUND in web.php<br>";
    } else {
        echo "❌ loto-isolations routes NOT FOUND in web.php<br>";
    }
    echo "Last modified: " . date('Y-m-d H:i:s', filemtime($routesPath)) . "<br>";
} else {
    echo "❌ web.php MISSING<br>";
}

// 5. Cek autoload
$autoloadPath = __DIR__ . '/vendor/autoload.php';
echo "<h3>5. Autoload Check:</h3>";
if (file_exists($autoloadPath)) {
    echo "✅ autoload.php EXISTS<br>";
    echo "Last modified: " . date('Y-m-d H:i:s', filemtime($autoloadPath)) . "<br>";
} else {
    echo "❌ autoload.php MISSING<br>";
}

// 6. Cek composer.json
$composerPath = __DIR__ . '/composer.json';
echo "<h3>6. Composer Check:</h3>";
if (file_exists($composerPath)) {
    echo "✅ composer.json EXISTS<br>";
    echo "Last modified: " . date('Y-m-d H:i:s', filemtime($composerPath)) . "<br>";
} else {
    echo "❌ composer.json MISSING<br>";
}

echo "<hr>";
echo "<h3>Next Steps:</h3>";
echo "1. Upload missing files shown above<br>";
echo "2. Run: composer dump-autoload<br>";
echo "3. Run: php artisan route:clear<br>";
echo "4. Run: php artisan route:cache<br>";
echo "5. Run: php artisan config:clear<br>";
?>