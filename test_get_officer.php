<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Officer;

echo "=== Test getOfficer Response Format ===" . PHP_EOL;

// Simulate the getOfficer controller method
$officer = Officer::find(1);

if ($officer) {
    // This is what the controller currently returns
    $controllerResponse = [
        'id' => $officer->id,
        'name' => $officer->name,
        'username' => $officer->username,
        'created_at' => $officer->created_at,
        'updated_at' => $officer->updated_at
    ];
    
    echo "Current controller response:" . PHP_EOL;
    echo json_encode($controllerResponse, JSON_PRETTY_PRINT) . PHP_EOL;
    
    echo PHP_EOL . "This should work correctly with the JavaScript editUser function." . PHP_EOL;
} else {
    echo "No officer found with ID 1" . PHP_EOL;
}
