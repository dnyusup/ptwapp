<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

use Illuminate\Support\Facades\DB;

$columns = DB::select('DESCRIBE hra_work_at_heights');

echo "Fields containing 'other' or 'work' or 'conditions':\n";
foreach ($columns as $col) {
    if (strpos($col->Field, 'other') !== false || 
        strpos($col->Field, 'work') !== false || 
        strpos($col->Field, 'conditions') !== false ||
        strpos($col->Field, 'area') !== false ||
        strpos($col->Field, 'cable') !== false ||
        strpos($col->Field, 'ventilation') !== false ||
        strpos($col->Field, 'machine') !== false ||
        strpos($col->Field, 'emergency') !== false ||
        strpos($col->Field, 'materials') !== false ||
        strpos($col->Field, 'safety') !== false) {
        echo "- " . $col->Field . "\n";
    }
}
