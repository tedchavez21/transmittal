<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Session Debug ===" . PHP_EOL;

// Check Laravel session
echo PHP_EOL . "=== Laravel Session ===" . PHP_EOL;
$session = app('session');
echo "Laravel email_logged_in: " . ($session->get('email_logged_in') ? 'true' : 'false') . PHP_EOL;
echo "Laravel email_user_name: " . ($session->get('email_user_name') ?? 'not set') . PHP_EOL;

// Test simulating the exact controller logic
echo PHP_EOL . "=== Simulating showEmailHandler ===" . PHP_EOL;

$session = app('session');
$emailUserName = $session->get('email_user_name');
echo "Email user name from session: " . ($emailUserName ?? 'null') . PHP_EOL;

if (!$session->get('email_logged_in')) {
    echo "User is NOT logged in - should show login form" . PHP_EOL;
} else {
    echo "User IS logged in - should show records" . PHP_EOL;
    
    if ($emailUserName) {
        echo "Testing query for user: $emailUserName" . PHP_EOL;
        
        $query = \App\Models\Record::where('source', 'Email')
            ->where('encoderName', $emailUserName);

        // Default to showing today's records if no date filters are enabled
        $hasDateFilters = false;

        // If no date filters are enabled, default to today's records
        if (!$hasDateFilters) {
            $query->whereDate('created_at', now()->toDateString());
        }

        $records = $query->orderBy('id', 'desc')->get(['id', 'encoderName', 'farmerName', 'created_at']);

        echo "Records that would be shown: " . $records->count() . PHP_EOL;
        foreach ($records as $r) {
            echo "  ID: {$r->id}, Farmer: {$r->farmerName}" . PHP_EOL;
        }
    } else {
        echo "Email user name is null - no records would be shown" . PHP_EOL;
    }
}
