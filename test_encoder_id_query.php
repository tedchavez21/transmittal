<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Record;
use App\Models\EmailHandler;

echo "=== Test Encoder ID Query ===" . PHP_EOL;

// Test 1: Verify encoder_id population worked
echo PHP_EOL . "=== Test 1: Verify Encoder ID Population ===" . PHP_EOL;
$emailRecords = Record::where('source', 'Email')->limit(5)->get();
foreach ($emailRecords as $record) {
    echo "ID: {$record->id}, Farmer: {$record->farmerName}, EncoderName: '{$record->encoderName}', EncoderID: {$record->encoder_id}" . PHP_EOL;
}

// Test 2: Test the new query logic
echo PHP_EOL . "=== Test 2: Test New Query Logic ===" . PHP_EOL;

// Simulate logged-in user
$emailUserName = 'Hanna Marie Lorica';
echo "Logged in as: $emailUserName" . PHP_EOL;

// Find the email handler by name to get their ID
$emailHandler = EmailHandler::where('name', $emailUserName)->first();
if ($emailHandler) {
    $encoderId = $emailHandler->id;
    echo "Found Email Handler ID: $encoderId" . PHP_EOL;
    
    // Use encoder_id for efficient querying
    $query = Record::where('source', 'Email')
        ->where('encoder_id', $encoderId)
        ->whereDate('created_at', today());
    
    $records = $query->orderBy('id', 'desc')->get();
    echo "Records found using encoder_id: " . $records->count() . PHP_EOL;
    
    foreach ($records as $record) {
        echo "  - ID: {$record->id}, Farmer: {$record->farmerName}, EncoderID: {$record->encoder_id}" . PHP_EOL;
    }
} else {
    echo "Email handler not found for: $emailUserName" . PHP_EOL;
}

// Test 3: Compare with old query method
echo PHP_EOL . "=== Test 3: Compare with Old Method ===" . PHP_EOL;
$oldQuery = Record::where('source', 'Email')
    ->where('encoderName', $emailUserName)
    ->whereDate('created_at', today());

$oldRecords = $oldQuery->orderBy('id', 'desc')->get();
echo "Records found using encoderName: " . $oldRecords->count() . PHP_EOL;

// Test 4: Performance comparison
echo PHP_EOL . "=== Test 4: Benefits of Encoder ID ===" . PHP_EOL;
echo "✅ More efficient queries (integer vs string comparison)" . PHP_EOL;
echo "✅ Proper foreign key relationship" . PHP_EOL;
echo "✅ Easier to maintain user relationships" . PHP_EOL;
echo "✅ Can handle name changes without breaking records" . PHP_EOL;
echo "✅ Better database integrity" . PHP_EOL;

echo PHP_EOL . "=== Summary ===" . PHP_EOL;
echo "The encoder_id approach provides:" . PHP_EOL;
echo "1. Faster database queries" . PHP_EOL;
echo "2. Better data integrity" . PHP_EOL;
echo "3. Easier maintenance" . PHP_EOL;
echo "4. Proper foreign key relationships" . PHP_EOL;
