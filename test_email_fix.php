<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Record;

echo "=== Testing Email Handler Fix ===" . PHP_EOL;

// Test the new logic - no date filters applied by default
$emailUserName = 'Hanna Marie Lorica';
echo "Testing for user: {$emailUserName}" . PHP_EOL;

// Simulate the new query logic (no filters by default)
$query = Record::where('source', 'Email')
    ->where('encoderName', $emailUserName);

$records = $query->orderBy('id', 'desc')->limit(25)->get(['id', 'encoderName', 'date_received', 'created_at']);

echo "Records found (new logic): " . $records->count() . PHP_EOL;

foreach ($records as $r) {
    echo "ID: {$r->id}, Date Received: {$r->date_received}, Created: {$r->created_at}" . PHP_EOL;
}

echo PHP_EOL . "=== Testing with Date Filter Enabled ===" . PHP_EOL;

// Simulate with date filter enabled
$queryWithDate = Record::where('source', 'Email')
    ->where('encoderName', $emailUserName)
    ->whereDate('date_received', now()->toDateString());

$recordsWithDate = $queryWithDate->orderBy('id', 'desc')->limit(25)->get(['id', 'encoderName', 'date_received', 'created_at']);

echo "Records found with date filter: " . $recordsWithDate->count() . PHP_EOL;

foreach ($recordsWithDate as $r) {
    echo "ID: {$r->id}, Date Received: {$r->date_received}, Created: {$r->created_at}" . PHP_EOL;
}
