<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Record;

echo "=== Live Email Handler Debug ===" . PHP_EOL;

// Simulate the exact query from showEmailHandler with different scenarios
$today = now()->toDateString();
echo "Today: $today" . PHP_EOL;

// Test 1: Simulate no filters (default behavior)
echo PHP_EOL . "=== Test 1: No Date Filters (Default) ===" . PHP_EOL;
$emailUserName = 'Hanna Marie Lorica'; // Change this to test different users

$query = Record::where('source', 'Email')
    ->where('encoderName', $emailUserName);

// Default to showing today's records if no date filters are enabled
$hasDateFilters = false;

// If no date filters are enabled, default to today's records
if (!$hasDateFilters) {
    $query->whereDate('created_at', $today);
}

$records = $query->orderBy('id', 'desc')->get(['id', 'encoderName', 'farmerName', 'created_at', 'date_received']);

echo "User: $emailUserName" . PHP_EOL;
echo "Records found: " . $records->count() . PHP_EOL;
foreach ($records as $r) {
    echo "  ID: {$r->id}, Farmer: {$r->farmerName}, Created: {$r->created_at}, Date Received: {$r->date_received}" . PHP_EOL;
}

// Test 2: Simulate with date received filter enabled
echo PHP_EOL . "=== Test 2: Date Received Filter Enabled ===" . PHP_EOL;
$query2 = Record::where('source', 'Email')
    ->where('encoderName', $emailUserName);

// Simulate date received filter being enabled
$enable_date_received = true;
$date_received = $today;

if ($enable_date_received && !empty($date_received)) {
    $query2->whereDate('date_received', $date_received);
    echo "Applied date_received filter: $date_received" . PHP_EOL;
}

$records2 = $query2->orderBy('id', 'desc')->get(['id', 'encoderName', 'farmerName', 'created_at', 'date_received']);

echo "Records found: " . $records2->count() . PHP_EOL;
foreach ($records2 as $r) {
    echo "  ID: {$r->id}, Farmer: {$r->farmerName}, Created: {$r->created_at}, Date Received: {$r->date_received}" . PHP_EOL;
}

// Test 3: Simulate with date encoded filter enabled
echo PHP_EOL . "=== Test 3: Date Encoded Filter Enabled ===" . PHP_EOL;
$query3 = Record::where('source', 'Email')
    ->where('encoderName', $emailUserName);

// Simulate date encoded filter being enabled
$enable_date_encoded = true;
$date_encoded = $today;

if ($enable_date_encoded && !empty($date_encoded)) {
    $query3->whereDate('created_at', $date_encoded);
    echo "Applied date_encoded filter: $date_encoded" . PHP_EOL;
}

$records3 = $query3->orderBy('id', 'desc')->get(['id', 'encoderName', 'farmerName', 'created_at', 'date_received']);

echo "Records found: " . $records3->count() . PHP_EOL;
foreach ($records3 as $r) {
    echo "  ID: {$r->id}, Farmer: {$r->farmerName}, Created: {$r->created_at}, Date Received: {$r->date_received}" . PHP_EOL;
}

// Test 4: Check if there are any records with different dates
echo PHP_EOL . "=== Test 4: All Email Records for User ===" . PHP_EOL;
$allRecords = Record::where('source', 'Email')
    ->where('encoderName', $emailUserName)
    ->orderBy('id', 'desc')
    ->get(['id', 'encoderName', 'farmerName', 'created_at', 'date_received']);

echo "Total records for $emailUserName: " . $allRecords->count() . PHP_EOL;
foreach ($allRecords as $r) {
    echo "  ID: {$r->id}, Farmer: {$r->farmerName}, Created: {$r->created_at}, Date Received: {$r->date_received}" . PHP_EOL;
}
