<?php

// Manual fix for specific badge patterns
$filePath = __DIR__ . '/ptw/resources/views/hra/hot-works/show.blade.php';

if (!file_exists($filePath)) {
    die("File not found: {$filePath}");
}

echo "🔧 Manual fix for specific patterns...\n";

$content = file_get_contents($filePath);

// Specific patterns that need to be replaced
$replacements = [
    // Badge classes
    '{{ $hraHotWork->q6_area_inspected === 1 ? \'badge-yes\' : ($hraHotWork->q6_area_inspected === 0 ? \'badge-no\' : \'badge-na\') }}' => '{{ $hraHotWork->q6_area_inspected ? \'badge-yes\' : \'badge-no\' }}',
    '{{ $hraHotWork->q7_flammable_structures === 1 ? \'badge-yes\' : ($hraHotWork->q7_flammable_structures === 0 ? \'badge-no\' : \'badge-na\') }}' => '{{ $hraHotWork->q7_flammable_structures ? \'badge-yes\' : \'badge-no\' }}',
    '{{ $hraHotWork->q8_fire_blanket === 1 ? \'badge-yes\' : ($hraHotWork->q8_fire_blanket === 0 ? \'badge-no\' : \'badge-na\') }}' => '{{ $hraHotWork->q8_fire_blanket ? \'badge-yes\' : \'badge-no\' }}',
    '{{ $hraHotWork->q9_valve_drain_covered === 1 ? \'badge-yes\' : ($hraHotWork->q9_valve_drain_covered === 0 ? \'badge-no\' : \'badge-na\') }}' => '{{ $hraHotWork->q9_valve_drain_covered ? \'badge-yes\' : \'badge-no\' }}',
    '{{ $hraHotWork->q10_isolation_ducting === 1 ? \'badge-yes\' : ($hraHotWork->q10_isolation_ducting === 0 ? \'badge-no\' : \'badge-na\') }}' => '{{ $hraHotWork->q10_isolation_ducting ? \'badge-yes\' : \'badge-no\' }}',
    '{{ $hraHotWork->q11_holes_sealed === 1 ? \'badge-yes\' : ($hraHotWork->q11_holes_sealed === 0 ? \'badge-no\' : \'badge-na\') }}' => '{{ $hraHotWork->q11_holes_sealed ? \'badge-yes\' : \'badge-no\' }}',
    '{{ $hraHotWork->q12_ventilation_adequate === 1 ? \'badge-yes\' : ($hraHotWork->q12_ventilation_adequate === 0 ? \'badge-no\' : \'badge-na\') }}' => '{{ $hraHotWork->q12_ventilation_adequate ? \'badge-yes\' : \'badge-no\' }}',
    '{{ $hraHotWork->q13_electrical_protected === 1 ? \'badge-yes\' : ($hraHotWork->q13_electrical_protected === 0 ? \'badge-no\' : \'badge-na\') }}' => '{{ $hraHotWork->q13_electrical_protected ? \'badge-yes\' : \'badge-no\' }}',
    '{{ $hraHotWork->q14_equipment_protected === 1 ? \'badge-yes\' : ($hraHotWork->q14_equipment_protected === 0 ? \'badge-no\' : \'badge-na\') }}' => '{{ $hraHotWork->q14_equipment_protected ? \'badge-yes\' : \'badge-no\' }}',
    
    // Badge text
    '{{ $hraHotWork->q6_area_inspected === 1 ? \'YA\' : ($hraHotWork->q6_area_inspected === 0 ? \'TIDAK\' : \'N/A\') }}' => '{{ $hraHotWork->q6_area_inspected ? \'YA\' : \'TIDAK\' }}',
    '{{ $hraHotWork->q7_flammable_structures === 1 ? \'YA\' : ($hraHotWork->q7_flammable_structures === 0 ? \'TIDAK\' : \'N/A\') }}' => '{{ $hraHotWork->q7_flammable_structures ? \'YA\' : \'TIDAK\' }}',
    '{{ $hraHotWork->q8_fire_blanket === 1 ? \'YA\' : ($hraHotWork->q8_fire_blanket === 0 ? \'TIDAK\' : \'N/A\') }}' => '{{ $hraHotWork->q8_fire_blanket ? \'YA\' : \'TIDAK\' }}',
    '{{ $hraHotWork->q9_valve_drain_covered === 1 ? \'YA\' : ($hraHotWork->q9_valve_drain_covered === 0 ? \'TIDAK\' : \'N/A\') }}' => '{{ $hraHotWork->q9_valve_drain_covered ? \'YA\' : \'TIDAK\' }}',
    '{{ $hraHotWork->q10_isolation_ducting === 1 ? \'YA\' : ($hraHotWork->q10_isolation_ducting === 0 ? \'TIDAK\' : \'N/A\') }}' => '{{ $hraHotWork->q10_isolation_ducting ? \'YA\' : \'TIDAK\' }}',
    '{{ $hraHotWork->q11_holes_sealed === 1 ? \'YA\' : ($hraHotWork->q11_holes_sealed === 0 ? \'TIDAK\' : \'N/A\') }}' => '{{ $hraHotWork->q11_holes_sealed ? \'YA\' : \'TIDAK\' }}',
    '{{ $hraHotWork->q12_ventilation_adequate === 1 ? \'YA\' : ($hraHotWork->q12_ventilation_adequate === 0 ? \'TIDAK\' : \'N/A\') }}' => '{{ $hraHotWork->q12_ventilation_adequate ? \'YA\' : \'TIDAK\' }}',
    '{{ $hraHotWork->q13_electrical_protected === 1 ? \'YA\' : ($hraHotWork->q13_electrical_protected === 0 ? \'TIDAK\' : \'N/A\') }}' => '{{ $hraHotWork->q13_electrical_protected ? \'YA\' : \'TIDAK\' }}',
    '{{ $hraHotWork->q14_equipment_protected === 1 ? \'YA\' : ($hraHotWork->q14_equipment_protected === 0 ? \'TIDAK\' : \'N/A\') }}' => '{{ $hraHotWork->q14_equipment_protected ? \'YA\' : \'TIDAK\' }}',
];

$replacedCount = 0;
foreach ($replacements as $search => $replace) {
    if (str_contains($content, $search)) {
        $content = str_replace($search, $replace, $content);
        $replacedCount++;
        echo "✓ Fixed pattern: " . substr($search, 19, 20) . "...\n";
    }
}

// Save the file
file_put_contents($filePath, $content);

echo "\n✅ Manual patterns fixed!\n";
echo "📊 Total replacements: {$replacedCount}\n";

// Verify
$remainingBadgeNa = substr_count($content, 'badge-na');
$remainingNA = substr_count($content, 'N/A');
echo "📊 Remaining: badge-na={$remainingBadgeNa}, N/A={$remainingNA}\n";

if ($remainingBadgeNa <= 2 && $remainingNA <= 0) {
    echo "🎉 All patterns successfully fixed!\n";
} else {
    echo "⚠️  Some patterns may still need attention\n";
}

?>