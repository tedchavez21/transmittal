<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Record;

echo "=== Debug Email Records ===" . PHP_EOL;
echo "Total records: " . Record::count() . PHP_EOL;
echo "Email records: " . Record::where('source', 'Email')->count() . PHP_EOL;
echo "Today email records: " . Record::where('source', 'Email')->whereDate('created_at', now()->toDateString())->count() . PHP_EOL;

echo PHP_EOL . "=== Today's Email Records ===" . PHP_EOL;
$records = Record::where('source', 'Email')
    ->whereDate('created_at', now()->toDateString())
    ->get(['id', 'encoderName', 'date_received', 'created_at']);

foreach ($records as $r) {
    echo "ID: {$r->id}, Encoder: {$r->encoderName}, Date Received: {$r->date_received}, Created: {$r->created_at}" . PHP_EOL;
}

echo PHP_EOL . "=== All Email Encoder Names ===" . PHP_EOL;
$encoderNames = Record::where('source', 'Email')->distinct()->pluck('encoderName');
foreach ($encoderNames as $name) {
    echo "- {$name}" . PHP_EOL;
}

echo PHP_EOL . "=== Testing Email Handler Query for Each User ===" . PHP_EOL;
foreach ($encoderNames as $emailUserName) {
    $query = Record::where('source', 'Email')
        ->where('encoderName', $emailUserName);

    echo "Query for '{$emailUserName}':" . PHP_EOL;
    echo "  Total: " . $query->count() . PHP_EOL;

    // Test with date filters
    $queryWithDate = Record::where('source', 'Email')
        ->where('encoderName', $emailUserName)
        ->whereDate('date_received', now()->toDateString());

    echo "  With date_received filter: " . $queryWithDate->count() . PHP_EOL;

    $queryWithCreated = Record::where('source', 'Email')
        ->where('encoderName', $emailUserName)
        ->whereDate('created_at', now()->toDateString());

    echo "  With created_at filter: " . $queryWithCreated->count() . PHP_EOL;
    echo PHP_EOL;
}
