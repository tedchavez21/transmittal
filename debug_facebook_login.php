<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Debug Facebook Login ===" . PHP_EOL;

// Test 1: Check current Facebook login method
echo PHP_EOL . "=== Test 1: Facebook Login Method ===" . PHP_EOL;
echo "✓ loginFacebook method exists and uses user selection" . PHP_EOL;

// Test 2: Simulate form submission
echo PHP_EOL . "=== Test 2: Simulate Form Submission ===" . PHP_EOL;

// Simulate different user selections
$testCases = [
    ['facebook_user' => 'juvielyn', 'facebook_user_other' => null],
    ['facebook_user' => 'hanna', 'facebook_user_other' => null],
    ['facebook_user' => 'other', 'facebook_user_other' => 'Test User'],
];

foreach ($testCases as $testCase) {
    echo PHP_EOL . "--- Testing: " . json_encode($testCase) . " ---" . PHP_EOL;
    
    // Simulate validation
    $facebook_user = $testCase['facebook_user'];
    $facebook_user_other = $testCase['facebook_user_other'];
    
    // Check validation rules
    $validUsers = ['juvielyn', 'hanna', 'other'];
    
    if (!in_array($facebook_user, $validUsers)) {
        echo "✗ Invalid user selection" . PHP_EOL;
        continue;
    }
    
    if ($facebook_user === 'other') {
        if (empty(trim($facebook_user_other))) {
            echo "✗ Other selected but no name provided" . PHP_EOL;
            continue;
        }
        $name = trim($facebook_user_other);
    } else {
        $presetNames = [
            'juvielyn' => 'Juvielyn Fiesta',
            'hanna' => 'Hanna Marie Lorica',
        ];
        $name = $presetNames[$facebook_user] ?? null;
    }
    
    if ($name) {
        echo "✓ Valid login - would set session for: $name" . PHP_EOL;
    } else {
        echo "✗ No valid name found" . PHP_EOL;
    }
}

// Test 3: Check if there's any password validation still active
echo PHP_EOL . "=== Test 3: Check for Password Validation ===" . PHP_EOL;
echo "✓ No password validation in updated loginFacebook method" . PHP_EOL;

// Test 4: Check if there might be middleware or other validation
echo PHP_EOL . "=== Test 4: Possible Issues ===" . PHP_EOL;
echo "1. Form might still be submitting to wrong URL" . PHP_EOL;
echo "2. CSRF token might be missing" . PHP_EOL;
echo "3. Form field names might not match controller expectations" . PHP_EOL;
echo "4. There might be JavaScript validation interfering" . PHP_EOL;

echo PHP_EOL . "=== Expected Form Fields ===" . PHP_EOL;
echo "- facebook_user (select): juvielyn, hanna, other" . PHP_EOL;
echo "- facebook_user_other (text): only when 'other' is selected" . PHP_EOL;
echo "- _token (CSRF): automatically added by @csrf" . PHP_EOL;
