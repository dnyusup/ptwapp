<?php

// Final fix for ALL badge patterns in HRA Hot Work
$filePath = __DIR__ . '/ptw/resources/views/hra/hot-works/show.blade.php';

if (!file_exists($filePath)) {
    die("File not found: {$filePath}");
}

echo "🔧 Final fix for ALL badge patterns...\n";

$content = file_get_contents($filePath);

// Get all field names that need fixing
preg_match_all('/\$hraHotWork->([a-z_]+) === 1 \? \'badge-yes\' : \(\$hraHotWork->[a-z_]+ === 0 \? \'badge-no\' : \'badge-na\'\)/', $content, $matches);
$fields = array_unique($matches[1]);

echo "Found " . count($fields) . " fields to fix:\n";
foreach ($fields as $field) {
    echo "  - {$field}\n";
}

// Fix all badge class patterns
$pattern1 = '/\$hraHotWork->([a-z_]+) === 1 \? \'badge-yes\' : \(\$hraHotWork->[a-z_]+ === 0 \? \'badge-no\' : \'badge-na\'\)/';
$replacement1 = '$hraHotWork->$1 ? \'badge-yes\' : \'badge-no\'';
$content = preg_replace($pattern1, $replacement1, $content);

// Fix all badge text patterns
$pattern2 = '/\$hraHotWork->([a-z_]+) === 1 \? \'YA\' : \(\$hraHotWork->[a-z_]+ === 0 \? \'TIDAK\' : \'N\/A\'\)/';
$replacement2 = '$hraHotWork->$1 ? \'YA\' : \'TIDAK\'';
$content = preg_replace($pattern2, $replacement2, $content);

// Additional pattern variations
$content = preg_replace('/\{\{\s*\$hraHotWork->([a-z_]+) === 1 \? \'badge-yes\' : \(\$hraHotWork->[a-z_]+ === 0 \? \'badge-no\' : \'badge-na\'\)\s*\}\}/', '{{ $hraHotWork->$1 ? \'badge-yes\' : \'badge-no\' }}', $content);
$content = preg_replace('/\{\{\s*\$hraHotWork->([a-z_]+) === 1 \? \'YA\' : \(\$hraHotWork->[a-z_]+ === 0 \? \'TIDAK\' : \'N\/A\'\)\s*\}\}/', '{{ $hraHotWork->$1 ? \'YA\' : \'TIDAK\' }}', $content);

// Save the file
file_put_contents($filePath, $content);

echo "\n✅ All badge patterns fixed!\n";
echo "🔄 File updated successfully\n";

// Verify - count remaining patterns
$remainingBadgeNa = substr_count($content, 'badge-na');
$remainingNA = substr_count($content, 'N/A');
echo "📊 Remaining patterns: badge-na={$remainingBadgeNa}, N/A={$remainingNA}\n";

// Only CSS should have badge-na
if ($remainingBadgeNa <= 2) {  // CSS definition and hover
    echo "✅ Only CSS definitions remain - Perfect!\n";
} else {
    echo "⚠️  Some badge-na patterns still exist\n";
}

?>