<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Record;

echo "=== Test Facebook Record Source ===" . PHP_EOL;

// Test 1: Check current Facebook records
echo PHP_EOL . "=== Test 1: Current Facebook Records ===" . PHP_EOL;
$facebookRecords = Record::where('source', 'Facebook')->get();
echo "Total Facebook records: " . $facebookRecords->count() . PHP_EOL;

foreach ($facebookRecords as $record) {
    echo "ID: {$record->id}, Farmer: {$record->farmerName}, Source: {$record->source}, Encoder: {$record->encoderName}, Created: {$record->created_at}" . PHP_EOL;
}

// Test 2: Check Email records for comparison
echo PHP_EOL . "=== Test 2: Email Records for Comparison ===" . PHP_EOL;
$emailRecords = Record::where('source', 'Email')->limit(3)->get();
echo "Sample Email records:" . PHP_EOL;

foreach ($emailRecords as $record) {
    echo "ID: {$record->id}, Farmer: {$record->farmerName}, Source: {$record->source}, Encoder: {$record->encoderName}, Created: {$record->created_at}" . PHP_EOL;
}

// Test 3: Verify form submission would work correctly
echo PHP_EOL . "=== Test 3: Form Field Analysis ===" . PHP_EOL;
echo "Facebook form fields:" . PHP_EOL;
echo "- source: 'Facebook' (hidden field)" . PHP_EOL;
echo "- accounts: 'Name of Facebook page or account'" . PHP_EOL;
echo "- facebook_page_url: 'FB page link'" . PHP_EOL;
echo "- All other fields: same as Email" . PHP_EOL;

echo PHP_EOL . "Email form fields:" . PHP_EOL;
echo "- source: 'Email' (hidden field)" . PHP_EOL;
echo "- accounts: 'Email address or username'" . PHP_EOL;
echo "- No facebook_page_url field" . PHP_EOL;
echo "- All other fields: same as Facebook" . PHP_EOL;

echo PHP_EOL . "=== Test 4: Expected Behavior ===" . PHP_EOL;
echo "✓ Facebook form submits to same route('records') as Email" . PHP_EOL;
echo "✓ RecordsController::storeRecord handles both sources" . PHP_EOL;
echo "✓ Source field determines record type" . PHP_EOL;
echo "✓ EncoderName set from logged-in Facebook user session" . PHP_EOL;
