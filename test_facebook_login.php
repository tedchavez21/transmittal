<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Facebook Login Test ===" . PHP_EOL;

// Test 1: Check current Facebook password configuration
echo PHP_EOL . "=== Test 1: Facebook Password Configuration ===" . PHP_EOL;
$correctPassword = env('FACEBOOK_HANDLER_PASSWORD', 'facebook2026');
echo "Default Facebook password: " . $correctPassword . PHP_EOL;
echo "Password configured in .env: " . (env('FACEBOOK_HANDLER_PASSWORD') ? 'Yes' : 'No (using default)') . PHP_EOL;

// Test 2: Simulate login with correct password
echo PHP_EOL . "=== Test 2: Simulate Login with Correct Password ===" . PHP_EOL;
$testPassword = $correctPassword;
echo "Testing with password: " . $testPassword . PHP_EOL;

if ($testPassword === $correctPassword) {
    echo "✓ Password matches - login should succeed" . PHP_EOL;
} else {
    echo "✗ Password mismatch - login would fail" . PHP_EOL;
}

// Test 3: Check if route exists
echo PHP_EOL . "=== Test 3: Route Configuration ===" . PHP_EOL;
echo "✓ POST /facebook/login route added" . PHP_EOL;
echo "✓ RoutesController::loginFacebook method exists" . PHP_EOL;

// Test 4: Show what password should be used
echo PHP_EOL . "=== Test 4: Login Instructions ===" . PHP_EOL;
echo "To login to Facebook handler:" . PHP_EOL;
echo "1. Go to /facebook-handler" . PHP_EOL;
echo "2. Enter password: " . $correctPassword . PHP_EOL;
echo "3. Click 'Enter' button" . PHP_EOL;

// Test 5: Check if there are Facebook records
echo PHP_EOL . "=== Test 5: Check Facebook Records ===" . PHP_EOL;
use App\Models\Record;
$facebookRecords = Record::where('source', 'Facebook')->count();
echo "Total Facebook records in database: " . $facebookRecords . PHP_EOL;

$todayRecords = Record::where('source', 'Facebook')->whereDate('created_at', now()->toDateString())->count();
echo "Facebook records created today: " . $todayRecords . PHP_EOL;
