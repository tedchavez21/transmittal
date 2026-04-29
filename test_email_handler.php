<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Record;

echo "=== Test Email Handler Query ===" . PHP_EOL;

// Test different scenarios
$today = now()->toDateString();
echo "Today: $today" . PHP_EOL;

// Test 1: Hanna Marie Lorica - today's records
echo PHP_EOL . "=== Hanna Marie Lorica - Today's Records ===" . PHP_EOL;
$records = Record::where('source', 'Email')
    ->where('encoderName', 'Hanna Marie Lorica')
    ->whereDate('created_at', $today)
    ->get(['id', 'encoderName', 'farmerName', 'created_at']);

echo "Count: " . $records->count() . PHP_EOL;
foreach ($records as $r) {
    echo "ID: {$r->id}, Farmer: {$r->farmerName}, Created: {$r->created_at}" . PHP_EOL;
}

// Test 2: Ted Eiden Chavez - today's records
echo PHP_EOL . "=== Ted Eiden Chavez - Today's Records ===" . PHP_EOL;
$records = Record::where('source', 'Email')
    ->where('encoderName', 'Ted Eiden Chavez')
    ->whereDate('created_at', $today)
    ->get(['id', 'encoderName', 'farmerName', 'created_at']);

echo "Count: " . $records->count() . PHP_EOL;
foreach ($records as $r) {
    echo "ID: {$r->id}, Farmer: {$r->farmerName}, Created: {$r->created_at}" . PHP_EOL;
}

// Test 3: All email records today
echo PHP_EOL . "=== All Email Records Today ===" . PHP_EOL;
$records = Record::where('source', 'Email')
    ->whereDate('created_at', $today)
    ->get(['id', 'encoderName', 'farmerName', 'created_at']);

echo "Count: " . $records->count() . PHP_EOL;
foreach ($records as $r) {
    echo "ID: {$r->id}, Encoder: {$r->encoderName}, Farmer: {$r->farmerName}, Created: {$r->created_at}" . PHP_EOL;
}

// Test 4: Check if there are any records with different encoder names
echo PHP_EOL . "=== All Email Encoder Names ===" . PHP_EOL;
$encoderNames = Record::where('source', 'Email')->distinct()->pluck('encoderName');
foreach ($encoderNames as $name) {
    $count = Record::where('source', 'Email')->where('encoderName', $name)->count();
    echo "- {$name}: {$count} records" . PHP_EOL;
}
