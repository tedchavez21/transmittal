<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Test partial match issue
$username_ted = 'ted';
$username_teddy = 'teddy';

echo "=== Partial Match Test ===" . PHP_EOL;
echo "Testing username: '$username_ted'" . PHP_EOL;
echo "Database username: '$username_teddy'" . PHP_EOL;
echo PHP_EOL;

$officer_ted = App\Models\Officer::where('username', $username_ted)->first();
$officer_teddy = App\Models\Officer::where('username', $username_teddy)->first();

echo "Query results:" . PHP_EOL;
echo "Search for 'ted': " . ($officer_ted ? 'FOUND' : 'NOT FOUND') . PHP_EOL;
echo "Search for 'teddy': " . ($officer_teddy ? 'FOUND' : 'NOT FOUND') . PHP_EOL;
echo PHP_EOL;

if ($officer_ted) {
    echo "SECURITY ISSUE: 'ted' incorrectly matched with user:" . PHP_EOL;
    echo "  - Username: " . $officer_ted->username . PHP_EOL;
    echo "  - Name: " . $officer_ted->name . PHP_EOL;
} else {
    echo "SECURITY OK: 'ted' correctly NOT found" . PHP_EOL;
}

if ($officer_teddy) {
    echo "Expected: 'teddy' correctly found:" . PHP_EOL;
    echo "  - Username: " . $officer_teddy->username . PHP_EOL;
    echo "  - Name: " . $officer_teddy->name . PHP_EOL;
} else {
    echo "ERROR: 'teddy' not found in database" . PHP_EOL;
}

echo PHP_EOL;
echo "=== Testing LIKE query (potential security issue) ===" . PHP_EOL;
$officer_like = App\Models\Officer::where('username', 'LIKE', '%ted%')->get();
echo "Users matching '%ted%': " . $officer_like->count() . PHP_EOL;
foreach ($officer_like as $officer) {
    echo "  - " . $officer->username . " (" . $officer->name . ")" . PHP_EOL;
}
?>
