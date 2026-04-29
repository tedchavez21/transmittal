<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Record;
use App\Models\Officer;
use App\Models\EmailHandler;
use Illuminate\Support\Facades\DB;

echo "=== Populate Encoder IDs for Existing Records ===" . PHP_EOL;

// Test 1: Create mapping arrays
echo PHP_EOL . "=== Test 1: Create User Mappings ===" . PHP_EOL;

// Map Email handlers by name
$emailHandlers = EmailHandler::all();
$emailMapping = [];
foreach ($emailHandlers as $handler) {
    $emailMapping[strtolower(trim($handler->name))] = $handler->id;
    echo "Email Mapping: '{$handler->name}' -> ID: {$handler->id}" . PHP_EOL;
}

// Map Officers by name  
$officers = Officer::all();
$officerMapping = [];
foreach ($officers as $officer) {
    $officerMapping[strtolower(trim($officer->name))] = $officer->id;
    echo "Officer Mapping: '{$officer->name}' -> ID: {$officer->id}" . PHP_EOL;
}

// Test 2: Update Email records
echo PHP_EOL . "=== Test 2: Update Email Records ===" . PHP_EOL;
$emailRecords = Record::where('source', 'Email')->whereNull('encoder_id')->get();
$emailUpdated = 0;

foreach ($emailRecords as $record) {
    $encoderName = strtolower(trim($record->encoderName));
    
    // Try to find matching email handler
    if (isset($emailMapping[$encoderName])) {
        $record->encoder_id = $emailMapping[$encoderName];
        $record->save();
        $emailUpdated++;
        echo "Updated Email Record ID {$record->id}: '{$record->encoderName}' -> encoder_id: {$record->encoder_id}" . PHP_EOL;
    } else {
        echo "No match found for Email Record ID {$record->id}: '{$record->encoderName}'" . PHP_EOL;
    }
}

echo "Email records updated: $emailUpdated" . PHP_EOL;

// Test 3: Update Facebook records (special handling)
echo PHP_EOL . "=== Test 3: Update Facebook Records ===" . PHP_EOL;
$facebookRecords = Record::where('source', 'Facebook')->whereNull('encoder_id')->get();
$facebookUpdated = 0;

foreach ($facebookRecords as $record) {
    // For Facebook, we'll use a special ID or create Facebook users
    // For now, let's use the first Email handler as a placeholder or create a mapping
    if ($record->encoderName === 'Facebook') {
        // Option 1: Use a special ID (e.g., 999 for Facebook)
        $record->encoder_id = 999; // Special ID for Facebook
        $record->save();
        $facebookUpdated++;
        echo "Updated Facebook Record ID {$record->id}: encoder_id: {$record->encoder_id}" . PHP_EOL;
    }
}

echo "Facebook records updated: $facebookUpdated" . PHP_EOL;

// Test 4: Update OD records
echo PHP_EOL . "=== Test 4: Update OD Records ===" . PHP_EOL;
$odRecords = Record::where('source', 'OD')->whereNull('encoder_id')->get();
$odUpdated = 0;

foreach ($odRecords as $record) {
    $encoderName = strtolower(trim($record->encoderName));
    
    // Try to find matching officer
    if (isset($officerMapping[$encoderName])) {
        $record->encoder_id = $officerMapping[$encoderName];
        $record->save();
        $odUpdated++;
        echo "Updated OD Record ID {$record->id}: '{$record->encoderName}' -> encoder_id: {$record->encoder_id}" . PHP_EOL;
    } else {
        echo "No match found for OD Record ID {$record->id}: '{$record->encoderName}'" . PHP_EOL;
    }
}

echo "OD records updated: $odUpdated" . PHP_EOL;

// Test 5: Summary
echo PHP_EOL . "=== Test 5: Summary ===" . PHP_EOL;
$totalUpdated = $emailUpdated + $facebookUpdated + $odUpdated;
echo "Total records updated: $totalUpdated" . PHP_EOL;

// Check remaining null encoder_ids
$remainingNull = Record::whereNull('encoder_id')->count();
echo "Records with null encoder_id remaining: $remainingNull" . PHP_EOL;

echo PHP_EOL . "=== Next Steps ===" . PHP_EOL;
echo "1. Run the migration: php artisan migrate" . PHP_EOL;
echo "2. Run this script to populate encoder_id values" . PHP_EOL;
echo "3. Update record creation logic to use encoder_id" . PHP_EOL;
echo "4. Update queries to use encoder_id instead of encoderName" . PHP_EOL;
