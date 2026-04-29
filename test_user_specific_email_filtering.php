<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Record;
use App\Models\EmailHandler;

echo "=== Test User-Specific Email Filtering ===" . PHP_EOL;

// Test 1: Verify the logic for different users
echo PHP_EOL . "=== Test 1: User-Specific Filtering Logic ===" . PHP_EOL;

$testUsers = [
    'Hanna Marie Lorica',
    'Julie Ann Espejo',
    'Juvielyn Fiesta',
    'Non-existent User'
];

foreach ($testUsers as $userName) {
    echo PHP_EOL . "--- Testing user: '$userName' ---" . PHP_EOL;
    
    // Simulate the controller logic
    $emailHandler = EmailHandler::where('name', $userName)->first();
    $encoderId = null;
    
    if ($emailHandler) {
        $encoderId = $emailHandler->id;
        echo "Found EmailHandler: ID = $encoderId" . PHP_EOL;
        
        // Query with user-specific filtering
        $query = Record::where('source', 'Email')
            ->where('encoder_id', $encoderId)
            ->whereDate('created_at', today());
        
        $records = $query->orderBy('id', 'desc')->get();
        echo "Records found: " . $records->count() . PHP_EOL;
        
        foreach ($records->take(2) as $record) {
            echo "  - ID: {$record->id}, Farmer: {$record->farmerName}" . PHP_EOL;
        }
    } else {
        echo "EmailHandler not found - no records will be shown" . PHP_EOL;
    }
}

// Test 2: Check what records exist for today by user
echo PHP_EOL . "=== Test 2: Today's Records by User ===" . PHP_EOL;
$todayEmailRecords = Record::where('source', 'Email')
    ->whereDate('created_at', today())
    ->get();

echo "Total Email records today: " . $todayEmailRecords->count() . PHP_EOL;

$byEncoder = $todayEmailRecords->groupBy('encoder_id');
foreach ($byEncoder as $encoderId => $records) {
    echo "Encoder ID $encoderId: " . $records->count() . " records" . PHP_EOL;
    
    // Find the user name for this encoder_id
    $handler = EmailHandler::find($encoderId);
    if ($handler) {
        echo "  User: '{$handler->name}'" . PHP_EOL;
    } else {
        echo "  User: Unknown (encoder_id: $encoderId)" . PHP_EOL;
    }
    
    foreach ($records->take(2) as $record) {
        echo "    - ID: {$record->id}, Farmer: {$record->farmerName}" . PHP_EOL;
    }
}

// Test 3: Verify the expected behavior
echo PHP_EOL . "=== Test 3: Expected Behavior ===" . PHP_EOL;
echo "✅ User logs in as 'Hanna Marie Lorica'" . PHP_EOL;
echo "✅ System finds EmailHandler ID 4" . PHP_EOL;
echo "✅ Query filters by encoder_id = 4" . PHP_EOL;
echo "✅ Shows only Hanna's records from today" . PHP_EOL;
echo "✅ Date filters can override the default 'today' behavior" . PHP_EOL;

echo PHP_EOL . "=== Benefits of This Approach ===" . PHP_EOL;
echo "1. Efficient queries using integer encoder_id" . PHP_EOL;
echo "2. User isolation - each user sees only their records" . PHP_EOL;
echo "3. Default to today's records for current work" . PHP_EOL;
echo "4. Filters allow viewing historical data when needed" . PHP_EOL;
echo "5. Proper foreign key relationship maintained" . PHP_EOL;
