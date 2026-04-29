<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Record;

echo "=== Email Handler Login Flow Test ===" . PHP_EOL;

// Test 1: Show what happens when NOT logged in
echo PHP_EOL . "=== Step 1: User NOT Logged In ===" . PHP_EOL;
echo "Status: User sees login form" . PHP_EOL;
echo "Records shown: 0 (login form displayed instead)" . PHP_EOL;

// Test 2: Simulate login for each available user
echo PHP_EOL . "=== Step 2: Simulate Login for Each User ===" . PHP_EOL;

$availableUsers = [
    'juvielyn' => 'Juvielyn Fiesta',
    'hanna' => 'Hanna Marie Lorica',
];

$today = now()->toDateString();

foreach ($availableUsers as $userKey => $userName) {
    echo PHP_EOL . "--- Logging in as: $userName ---" . PHP_EOL;
    
    // Simulate the query that would run after login
    $query = Record::where('source', 'Email')
        ->where('encoderName', $userName);

    // Default to showing today's records (no date filters enabled)
    $hasDateFilters = false;
    if (!$hasDateFilters) {
        $query->whereDate('created_at', $today);
    }

    $records = $query->orderBy('id', 'desc')->get(['id', 'encoderName', 'farmerName', 'created_at']);

    echo "Records that would be shown: " . $records->count() . PHP_EOL;
    
    if ($records->count() > 0) {
        foreach ($records as $r) {
            echo "  ID: {$r->id}, Farmer: {$r->farmerName}, Created: {$r->created_at}" . PHP_EOL;
        }
    } else {
        echo "  No records found for today" . PHP_EOL;
    }
}

// Test 3: Show what "Other" user would need to do
echo PHP_EOL . "=== Step 3: For 'Other' Users ===" . PHP_EOL;
echo "If you're not in the preset list, you need to:" . PHP_EOL;
echo "1. Select 'Other' from the dropdown" . PHP_EOL;
echo "2. Type your full name (e.g., 'Ted Eiden Chavez')" . PHP_EOL;
echo "3. Click Continue" . PHP_EOL;

// Test 4: Check if "Ted Eiden Chavez" has records today
echo PHP_EOL . "=== Step 4: Test 'Ted Eiden Chavez' (Other user) ===" . PHP_EOL;
$otherUserName = 'Ted Eiden Chavez';
$query = Record::where('source', 'Email')
    ->where('encoderName', $otherUserName)
    ->whereDate('created_at', $today);

$records = $query->orderBy('id', 'desc')->get(['id', 'encoderName', 'farmerName', 'created_at']);

echo "Records for '$otherUserName': " . $records->count() . PHP_EOL;
foreach ($records as $r) {
    echo "  ID: {$r->id}, Farmer: {$r->farmerName}, Created: {$r->created_at}" . PHP_EOL;
}

echo PHP_EOL . "=== SOLUTION ===" . PHP_EOL;
echo "To see records in the email handler:" . PHP_EOL;
echo "1. Go to /email-handler page" . PHP_EOL;
echo "2. Select your name from the dropdown OR select 'Other' and type your name" . PHP_EOL;
echo "3. Click 'Continue' to log in" . PHP_EOL;
echo "4. You should then see today's records for your account" . PHP_EOL;
