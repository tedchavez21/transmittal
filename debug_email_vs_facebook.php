<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Record;

echo "=== Debug Email vs Facebook Record Fetching ===" . PHP_EOL;

// Test 1: Check current email records
echo PHP_EOL . "=== Test 1: Email Records Analysis ===" . PHP_EOL;
$emailRecords = Record::where('source', 'Email')->get();
echo "Total Email records: " . $emailRecords->count() . PHP_EOL;

// Group by encoder
$emailByEncoder = $emailRecords->groupBy('encoderName');
foreach ($emailByEncoder as $encoder => $records) {
    echo "Encoder: $encoder - Count: " . $records->count() . PHP_EOL;
}

// Check today's email records
$todayEmailRecords = Record::where('source', 'Email')
    ->whereDate('created_at', now()->toDateString())
    ->get();
echo "Today's Email records: " . $todayEmailRecords->count() . PHP_EOL;

// Test 2: Check Facebook records
echo PHP_EOL . "=== Test 2: Facebook Records Analysis ===" . PHP_EOL;
$facebookRecords = Record::where('source', 'Facebook')->get();
echo "Total Facebook records: " . $facebookRecords->count() . PHP_EOL;

// Group by encoder
$facebookByEncoder = $facebookRecords->groupBy('encoderName');
foreach ($facebookByEncoder as $encoder => $records) {
    echo "Encoder: $encoder - Count: " . $records->count() . PHP_EOL;
}

// Check today's Facebook records
$todayFacebookRecords = Record::where('source', 'Facebook')
    ->whereDate('created_at', now()->toDateString())
    ->get();
echo "Today's Facebook records: " . $todayFacebookRecords->count() . PHP_EOL;

// Test 3: Simulate email handler logic
echo PHP_EOL . "=== Test 3: Email Handler Logic Simulation ===" . PHP_EOL;

// Test with different user names
$testUsers = ['Hanna Marie Lorica', 'Juvielyn Fiesta', 'Ted Eiden Chavez'];

foreach ($testUsers as $user) {
    echo PHP_EOL . "--- Testing for user: $user ---" . PHP_EOL;
    
    // Simulate the email handler query
    $query = Record::where('source', 'Email')
        ->where('encoderName', $user)
        ->whereDate('created_at', today());
    
    $userRecords = $query->get();
    echo "Records for $user today: " . $userRecords->count() . PHP_EOL;
    
    if ($userRecords->count() > 0) {
        foreach ($userRecords->take(3) as $record) {
            echo "  - ID: {$record->id}, Farmer: {$record->farmerName}, Created: {$record->created_at}" . PHP_EOL;
        }
    }
}

// Test 4: Check if email handler should be simplified like Facebook
echo PHP_EOL . "=== Test 4: Logic Comparison ===" . PHP_EOL;
echo "Facebook handler logic:" . PHP_EOL;
echo "- source = 'Facebook'" . PHP_EOL;
echo "- whereDate('created_at', today())" . PHP_EOL;
echo "- No user filtering" . PHP_EOL;
echo "- No date filter toggles" . PHP_EOL;

echo PHP_EOL . "Email handler logic:" . PHP_EOL;
echo "- source = 'Email'" . PHP_EOL;
echo "- where('encoderName', \$emailUserName)" . PHP_EOL;
echo "- whereDate('created_at', today()) if no filters" . PHP_EOL;
echo "- Complex date filter toggles" . PHP_EOL;

echo PHP_EOL . "=== Recommendation ===" . PHP_EOL;
echo "Email handler should work correctly if:" . PHP_EOL;
echo "1. User is logged in (session has email_user_name)" . PHP_EOL;
echo "2. Records exist with that encoderName" . PHP_EOL;
echo "3. Records were created today" . PHP_EOL;
echo "4. No date filters are interfering" . PHP_EOL;
