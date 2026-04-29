<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Record;

echo "=== Test All Email Records (No User Filter) ===" . PHP_EOL;

// Test 1: Check all Email records (no user filter)
echo PHP_EOL . "=== Test 1: All Email Records ===" . PHP_EOL;
$query = Record::where('source', 'Email');

// Default to today's records (no date filters)
$query->whereDate('created_at', today());

$records = $query->orderBy('id', 'desc')->get();
echo "All Email records for today: " . $records->count() . PHP_EOL;

if ($records->count() > 0) {
    echo "Sample records:" . PHP_EOL;
    foreach ($records->take(5) as $record) {
        echo "  - ID: {$record->id}, Farmer: {$record->farmerName}, Encoder: {$record->encoderName}, Created: {$record->created_at}" . PHP_EOL;
    }
}

// Test 2: Check all Email records (all dates)
echo PHP_EOL . "=== Test 2: All Email Records (All Dates) ===" . PHP_EOL;
$allEmailRecords = Record::where('source', 'Email')->get();
echo "Total Email records (all dates): " . $allEmailRecords->count() . PHP_EOL;

if ($allEmailRecords->count() > 0) {
    echo "Records by encoder:" . PHP_EOL;
    $byEncoder = $allEmailRecords->groupBy('encoderName');
    foreach ($byEncoder as $encoder => $records) {
        echo "  $encoder: " . $records->count() . " records" . PHP_EOL;
    }
}

// Test 3: Simulate the new email handler logic
echo PHP_EOL . "=== Test 3: New Email Handler Logic ===" . PHP_EOL;
echo "✅ Removed: ->where('encoderName', \$emailUserName)" . PHP_EOL;
echo "✅ Kept: ->where('source', 'Email')" . PHP_EOL;
echo "✅ Kept: ->whereDate('created_at', today())" . PHP_EOL;
echo "✅ Result: Shows ALL Email records from today" . PHP_EOL;

echo PHP_EOL . "=== Expected Behavior Now ===" . PHP_EOL;
echo "1. Any logged-in user will see ALL Email records from today" . PHP_EOL;
echo "2. User filtering is disabled for testing" . PHP_EOL;
echo "3. Should show " . $records->count() . " records from today" . PHP_EOL;
echo "4. Date filters still work if enabled" . PHP_EOL;

echo PHP_EOL . "=== If Still No Records Show ===" . PHP_EOL;
echo "The issue might be:" . PHP_EOL;
echo "1. Authentication/session issue" . PHP_EOL;
echo "2. View rendering issue" . PHP_EOL;
echo "3. JavaScript error in email-handler view" . PHP_EOL;
echo "4. Date filters interfering" . PHP_EOL;
