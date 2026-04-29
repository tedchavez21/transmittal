<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Officer;

echo "=== Debug User Maintenance ===" . PHP_EOL;

// Test 1: Check if there are any officers in the database
echo PHP_EOL . "=== Test 1: Check Officers in Database ===" . PHP_EOL;
$officers = Officer::all();
echo "Total officers found: " . $officers->count() . PHP_EOL;

foreach ($officers as $officer) {
    echo "ID: {$officer->id}, Name: {$officer->name}, Username: {$officer->username}, Created: {$officer->created_at}" . PHP_EOL;
}

// Test 2: Test the getOfficers endpoint response format
echo PHP_EOL . "=== Test 2: Simulate getOfficers Response ===" . PHP_EOL;
if ($officers->count() > 0) {
    $responseData = [
        'success' => true,
        'officers' => $officers->map(function ($officer) {
            return [
                'id' => $officer->id,
                'name' => $officer->name,
                'username' => $officer->username,
                'created_at' => $officer->created_at,
                'updated_at' => $officer->updated_at
            ];
        })->toArray()
    ];
    
    echo "Response format that should be returned:" . PHP_EOL;
    echo json_encode($responseData, JSON_PRETTY_PRINT) . PHP_EOL;
} else {
    echo "No officers found - this might be the issue!" . PHP_EOL;
}

// Test 3: Test individual officer retrieval
echo PHP_EOL . "=== Test 3: Test Individual Officer Retrieval ===" . PHP_EOL;
if ($officers->count() > 0) {
    $firstOfficer = $officers->first();
    echo "Testing getOfficer for ID: {$firstOfficer->id}" . PHP_EOL;
    
    $officerData = [
        'id' => $firstOfficer->id,
        'name' => $firstOfficer->name,
        'username' => $firstOfficer->username,
        'created_at' => $firstOfficer->created_at,
        'updated_at' => $firstOfficer->updated_at
    ];
    
    echo "Individual officer data:" . PHP_EOL;
    echo json_encode($officerData, JSON_PRETTY_PRINT) . PHP_EOL;
} else {
    echo "No officers to test individual retrieval" . PHP_EOL;
}

// Test 4: Check database connection and table structure
echo PHP_EOL . "=== Test 4: Database Table Info ===" . PHP_EOL;
try {
    $tableExists = \Illuminate\Support\Facades\Schema::hasTable('officers');
    echo "Officers table exists: " . ($tableExists ? 'Yes' : 'No') . PHP_EOL;
    
    if ($tableExists) {
        $columns = \Illuminate\Support\Facades\Schema::getColumnListing('officers');
        echo "Table columns: " . implode(', ', $columns) . PHP_EOL;
    }
} catch (Exception $e) {
    echo "Error checking table: " . $e->getMessage() . PHP_EOL;
}
