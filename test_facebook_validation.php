<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Test Facebook Login Validation ===" . PHP_EOL;

// Test the exact validation rules from the controller
echo PHP_EOL . "=== Test Validation Rules ===" . PHP_EOL;

$testCases = [
    // Valid cases
    ['facebook_user' => 'juvielyn', 'facebook_user_other' => '', 'expected' => 'valid'],
    ['facebook_user' => 'hanna', 'facebook_user_other' => '', 'expected' => 'valid'],
    ['facebook_user' => 'other', 'facebook_user_other' => 'Test User', 'expected' => 'valid'],
    
    // Invalid cases
    ['facebook_user' => 'invalid', 'facebook_user_other' => '', 'expected' => 'invalid'],
    ['facebook_user' => 'other', 'facebook_user_other' => '', 'expected' => 'invalid'],
    ['facebook_user' => 'other', 'facebook_user_other' => '   ', 'expected' => 'invalid'],
    ['facebook_user' => '', 'facebook_user_other' => '', 'expected' => 'invalid'],
];

foreach ($testCases as $testCase) {
    echo PHP_EOL . "--- Testing: " . json_encode($testCase) . " ---" . PHP_EOL;
    
    $facebook_user = $testCase['facebook_user'];
    $facebook_user_other = $testCase['facebook_user_other'];
    $expected = $testCase['expected'];
    
    // Simulate Laravel validation
    $errors = [];
    
    // Rule: facebook_user required|string|in:juvielyn,hanna,other
    if (empty($facebook_user)) {
        $errors[] = 'facebook_user is required';
    }
    
    if (!in_array($facebook_user, ['juvielyn', 'hanna', 'other'])) {
        $errors[] = 'facebook_user must be one of: juvielyn, hanna, other';
    }
    
    // Rule: facebook_user_other nullable|string|max:255
    if ($facebook_user === 'other') {
        if (empty(trim($facebook_user_other))) {
            $errors[] = 'facebook_user_other is required when facebook_user is other';
        }
    }
    
    if (empty($errors)) {
        $result = 'valid';
        echo "✓ Validation passed" . PHP_EOL;
        
        // Test name resolution
        $presetNames = [
            'juvielyn' => 'Juvielyn Fiesta',
            'hanna' => 'Hanna Marie Lorica',
        ];
        
        if ($facebook_user === 'other') {
            $name = trim($facebook_user_other);
        } else {
            $name = $presetNames[$facebook_user] ?? null;
        }
        
        echo "  Resolved name: $name" . PHP_EOL;
    } else {
        $result = 'invalid';
        echo "✗ Validation failed:" . PHP_EOL;
        foreach ($errors as $error) {
            echo "  - $error" . PHP_EOL;
        }
    }
    
    if ($result !== $expected) {
        echo "⚠️  Expected $expected but got $result" . PHP_EOL;
    }
}

echo PHP_EOL . "=== Check for Other Issues ===" . PHP_EOL;
echo "1. Make sure form fields match validation rules exactly" . PHP_EOL;
echo "2. Check if there's any JavaScript validation preventing submission" . PHP_EOL;
echo "3. Verify CSRF token is present" . PHP_EOL;
echo "4. Check browser network tab for actual request details" . PHP_EOL;
