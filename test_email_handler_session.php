<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Test Email Handler Session Logic ===" . PHP_EOL;

// Test 1: Simulate different session states
echo PHP_EOL . "=== Test 1: Session State Simulation ===" . PHP_EOL;

// Simulate no login
echo "--- No login state ---" . PHP_EOL;
$emailUserName = null;
$records = collect();
echo "emailUserName: " . ($emailUserName ?? 'null') . PHP_EOL;
echo "Records count: " . $records->count() . PHP_EOL;

// Simulate logged in user
echo PHP_EOL . "--- Logged in as Hanna Marie Lorica ---" . PHP_EOL;
$emailUserName = 'Hanna Marie Lorica';

if ($emailUserName) {
    $query = \App\Models\Record::where('source', 'Email')
        ->where('encoderName', $emailUserName);

    // Default to showing today's records if no date filters are enabled
    $hasDateFilters = false;
    
    // If no date filters are enabled, default to today's records
    if (!$hasDateFilters) {
        $query->whereDate('created_at', now()->toDateString());
    }

    $records = $query->orderBy('id', 'desc')
        ->paginate(25)
        ->withQueryString();
    
    echo "Records found: " . $records->count() . PHP_EOL;
    echo "Total records available: " . $records->total() . PHP_EOL;
    
    if ($records->count() > 0) {
        echo "Sample records:" . PHP_EOL;
        foreach ($records->take(3) as $record) {
            echo "  - ID: {$record->id}, Farmer: {$record->farmerName}, Created: {$record->created_at}" . PHP_EOL;
        }
    }
}

// Test 2: Check if there are any records for today
echo PHP_EOL . "=== Test 2: Today's Records Check ===" . PHP_EOL;
$todayRecords = \App\Models\Record::where('source', 'Email')
    ->whereDate('created_at', now()->toDateString())
    ->get();

echo "All Email records created today: " . $todayRecords->count() . PHP_EOL;

if ($todayRecords->count() > 0) {
    echo "Today's records by encoder:" . PHP_EOL;
    $byEncoder = $todayRecords->groupBy('encoderName');
    foreach ($byEncoder as $encoder => $records) {
        echo "  $encoder: " . $records->count() . " records" . PHP_EOL;
    }
}

// Test 3: Debug what might be wrong
echo PHP_EOL . "=== Test 3: Debug Checklist ===" . PHP_EOL;
echo "If email handler shows no records, check:" . PHP_EOL;
echo "1. Is user logged in? (session has email_user_name)" . PHP_EOL;
echo "2. Does the user have records for today?" . PHP_EOL;
echo "3. Are the date filters interfering?" . PHP_EOL;
echo "4. Is the session being maintained?" . PHP_EOL;

echo PHP_EOL . "=== Expected Behavior ===" . PHP_EOL;
echo "✓ User logs in as 'Hanna Marie Lorica'" . PHP_EOL;
echo "✓ Should see 2 records from today" . PHP_EOL;
echo "✓ Should not see records from other users" . PHP_EOL;
echo "✓ Should not see records from other days (unless filtered)" . PHP_EOL;
