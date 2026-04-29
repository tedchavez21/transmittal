<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Record;
use App\Models\EmailHandler;

echo "=== Debug Email Handler After Encoder ID Implementation ===" . PHP_EOL;

// Test 1: Check the exact logic from the controller
echo PHP_EOL . "=== Test 1: Simulate Controller Logic ===" . PHP_EOL;

// Simulate session
$emailUserName = 'Hanna Marie Lorica'; // This would come from session
echo "Simulated emailUserName: '$emailUserName'" . PHP_EOL;

// Get the logged-in user's encoder ID
$encoderId = null;
if ($emailUserName) {
    // Find the email handler by name to get their ID
    $emailHandler = EmailHandler::where('name', $emailUserName)->first();
    if ($emailHandler) {
        $encoderId = $emailHandler->id;
        echo "Found Email Handler: ID = $encoderId, Name = '{$emailHandler->name}'" . PHP_EOL;
    } else {
        echo "ERROR: Email handler not found for name: '$emailUserName'" . PHP_EOL;
    }
} else {
    echo "ERROR: No emailUserName in session" . PHP_EOL;
}

// Test 2: Check the query
echo PHP_EOL . "=== Test 2: Test the Query ===" . PHP_EOL;

$query = Record::where('source', 'Email');
echo "Base query: source = 'Email'" . PHP_EOL;

// Filter by logged-in user's encoder ID
if ($encoderId) {
    $query->where('encoder_id', $encoderId);
    echo "Added filter: encoder_id = $encoderId" . PHP_EOL;
} else {
    echo "WARNING: No encoder_id filter applied" . PHP_EOL;
}

// Default to showing today's records if no date filters are enabled
$hasDateFilters = false;
echo "Date filters enabled: " . ($hasDateFilters ? 'Yes' : 'No') . PHP_EOL;

// If no date filters are enabled, default to today's records
if (!$hasDateFilters) {
    $query->whereDate('created_at', now()->toDateString());
    echo "Added filter: created_at = today()" . PHP_EOL;
}

// Get the results
$records = $query->orderBy('id', 'desc')->get();
echo "Records found: " . $records->count() . PHP_EOL;

if ($records->count() > 0) {
    echo "Sample records:" . PHP_EOL;
    foreach ($records->take(3) as $record) {
        echo "  - ID: {$record->id}, Farmer: {$record->farmerName}, EncoderID: {$record->encoder_id}" . PHP_EOL;
    }
} else {
    echo "No records found!" . PHP_EOL;
}

// Test 3: Check what records should exist
echo PHP_EOL . "=== Test 3: Check What Records Should Exist ===" . PHP_EOL;
$allEmailToday = Record::where('source', 'Email')
    ->whereDate('created_at', now()->toDateString())
    ->get();

echo "All Email records today: " . $allEmailToday->count() . PHP_EOL;
foreach ($allEmailToday as $record) {
    echo "  - ID: {$record->id}, Farmer: {$record->farmerName}, EncoderName: '{$record->encoderName}', EncoderID: {$record->encoder_id}" . PHP_EOL;
}

// Test 4: Check EmailHandler table
echo PHP_EOL . "=== Test 4: Check EmailHandler Table ===" . PHP_EOL;
$emailHandlers = EmailHandler::all();
echo "Available Email Handlers:" . PHP_EOL;
foreach ($emailHandlers as $handler) {
    echo "  - ID: {$handler->id}, Name: '{$handler->name}', Active: {$handler->active}" . PHP_EOL;
}

echo PHP_EOL . "=== Possible Issues ===" . PHP_EOL;
echo "1. Session emailUserName doesn't match EmailHandler.name exactly" . PHP_EOL;
echo "2. EmailHandler.name has different spacing/casing than session" . PHP_EOL;
echo "3. encoder_id is null for some records" . PHP_EOL;
echo "4. Date filtering is interfering" . PHP_EOL;
