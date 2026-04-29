<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Record;

echo "=== Test Email Login Simulation ===" . PHP_EOL;

// Simulate different user sessions
$users = ['Hanna Marie Lorica', 'Ted Eiden Chavez', 'Julie Ann Espejo'];
$today = now()->toDateString();

foreach ($users as $emailUserName) {
    echo PHP_EOL . "=== Testing as: {$emailUserName} ===" . PHP_EOL;
    
    // Simulate the query from showEmailHandler
    $query = Record::where('source', 'Email')
        ->where('encoderName', $emailUserName);

    // Default to showing today's records (no date filters enabled)
    $hasDateFilters = false;
    
    // If no date filters are enabled, default to today's records
    if (!$hasDateFilters) {
        $query->whereDate('created_at', $today);
    }

    $records = $query->orderBy('id', 'desc')->get(['id', 'encoderName', 'farmerName', 'created_at']);

    echo "Records found: " . $records->count() . PHP_EOL;
    
    foreach ($records as $r) {
        echo "  ID: {$r->id}, Farmer: {$r->farmerName}, Created: {$r->created_at}" . PHP_EOL;
    }
}

echo PHP_EOL . "=== Test All Email Records Today (for admin view) ===" . PHP_EOL;
$allRecords = Record::where('source', 'Email')
    ->whereDate('created_at', $today)
    ->orderBy('id', 'desc')
    ->get(['id', 'encoderName', 'farmerName', 'created_at']);

echo "Total email records today: " . $allRecords->count() . PHP_EOL;
foreach ($allRecords as $r) {
    echo "  ID: {$r->id}, Encoder: {$r->encoderName}, Farmer: {$r->farmerName}" . PHP_EOL;
}
