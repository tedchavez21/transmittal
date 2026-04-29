<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Officer;

echo "=== Test Officer Full Name Logic ===" . PHP_EOL;

// Test 1: Check Officer table and session mapping
echo PHP_EOL . "=== Test 1: Officer Data ===" . PHP_EOL;

// Simulate different session scenarios
$testScenarios = [
    ['officer_id' => 2, 'officer_name' => 'ted'], // Should find 'Ted Eiden Chavez'
    ['officer_id' => 4, 'officer_name' => 'h.lorica'], // Should find 'Hanna Marie Lorica'
    ['officer_id' => 8, 'officer_name' => 'Juvy'], // Should find 'Juvielyn C. Fiesta'
    ['officer_id' => null, 'officer_name' => null], // Should find nothing
];

foreach ($testScenarios as $scenario) {
    echo PHP_EOL . "--- Testing scenario: " . json_encode($scenario) . " ---" . PHP_EOL;
    
    // Simulate the controller logic
    $officerId = $scenario['officer_id'];
    $officerName = $scenario['officer_name'];
    
    echo "Session officer_id: $officerId" . PHP_EOL;
    echo "Session officer_name: $officerName" . PHP_EOL;
    
    // Get the logged-in officer's full name from Officer table
    $officerFullName = null;
    if ($officerId) {
        $officer = Officer::find($officerId);
        if ($officer) {
            $officerFullName = $officer->name;
        }
    }
    
    echo "Retrieved officerFullName: $officerFullName" . PHP_EOL;
    
    // Check if this matches expected
    $expectedNames = [
        2 => 'Ted Eiden Chavez',
        4 => 'Hanna Marie Lorica',
        8 => 'Juvielyn C. Fiesta'
    ];
    
    $expectedName = $expectedNames[$officerId] ?? 'Unknown';
    echo "Expected name: $expectedName" . PHP_EOL;
    echo "Match: " . ($officerFullName === $expectedName ? 'YES' : 'NO') . PHP_EOL;
    
    if ($officerFullName !== $expectedName) {
        echo "❌ MISMATCH detected!" . PHP_EOL;
        echo "This could cause issues with record filtering." . PHP_EOL;
    }
}

echo PHP_EOL . "=== Test 2: Available Officers ===" . PHP_EOL;
$officers = Officer::all();
echo "Available officers:" . PHP_EOL;
foreach ($officers as $officer) {
    echo "  ID: {$officer->id}, Name: '{$officer->name}', Username: '{$officer->username}'" . PHP_EOL;
}

echo PHP_EOL . "=== Recommendations ===" . PHP_EOL;
echo "1. Check if session officer_id matches actual officer.id" . PHP_EOL;
echo "2. Verify officer lookup is working correctly" . PHP_EOL;
echo "3. Ensure full name is passed to view" . PHP_EOL;
echo "4. Test with actual session data" . PHP_EOL;
