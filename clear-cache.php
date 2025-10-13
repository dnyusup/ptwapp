<?php
/**
 * Clear Cache Script for Hostinger
 * Upload this file to public_html folder and access via browser
 * URL: https://yourdomain.com/clear-cache.php
 */

// Set time limit
set_time_limit(300);

// Change to Laravel root directory
$laravelRoot = __DIR__ . '/ptw';
if (!is_dir($laravelRoot)) {
    die('Laravel directory not found. Please adjust the path.');
}

chdir($laravelRoot);

echo "<h2>🚀 Clearing Laravel Cache on Hostinger</h2>";
echo "<pre>";

// Array of cache clear commands
$commands = [
    'php artisan cache:clear' => 'Application Cache',
    'php artisan config:clear' => 'Configuration Cache', 
    'php artisan route:clear' => 'Route Cache',
    'php artisan view:clear' => 'View Cache',
    'php artisan optimize:clear' => 'All Optimizations'
];

foreach ($commands as $command => $description) {
    echo "\n📋 Clearing {$description}...\n";
    echo "Command: {$command}\n";
    
    $output = [];
    $returnCode = 0;
    
    exec($command . ' 2>&1', $output, $returnCode);
    
    if ($returnCode === 0) {
        echo "✅ SUCCESS: " . implode("\n", $output) . "\n";
    } else {
        echo "❌ ERROR: " . implode("\n", $output) . "\n";
    }
    
    echo str_repeat('-', 50) . "\n";
}

// Also try to update approval_status for existing HRA records
echo "\n🔄 Updating existing HRA Hot Work records...\n";

try {
    // Include Laravel bootstrap
    require_once $laravelRoot . '/vendor/autoload.php';
    $app = require_once $laravelRoot . '/bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();
    
    // Update records
    $updated = \App\Models\HraHotWork::whereNull('approval_status')
                                   ->update(['approval_status' => 'draft']);
    
    echo "✅ Updated {$updated} HRA Hot Work records with default approval_status\n";
    
} catch (Exception $e) {
    echo "❌ Database update failed: " . $e->getMessage() . "\n";
}

echo "\n🎉 Cache clearing completed!\n";
echo "🔗 You can now delete this file for security.\n";
echo "</pre>";

// Optional: Auto-delete this file after execution (uncomment if needed)
// unlink(__FILE__);
?>