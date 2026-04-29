<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Http\Controllers\RoutesController;

echo "=== Test API Endpoints Directly ===" . PHP_EOL;

// Simulate admin session
session_start();
$_SESSION['admin_logged_in'] = true;
$_SESSION['admin_username'] = 'UZZIEL';

echo PHP_EOL . "=== Test 1: getOfficers Endpoint ===" . PHP_EOL;

// Create a mock request
class MockRequest {
    public function session($key = null) {
        global $_SESSION;
        if ($key === null) {
            return (object) $_SESSION;
        }
        return $_SESSION[$key] ?? null;
    }
    
    public function has($key) {
        global $_SESSION;
        return isset($_SESSION[$key]);
    }
    
    public function get($key, $default = null) {
        global $_SESSION;
        return $_SESSION[$key] ?? $default;
    }
}

$mockRequest = new MockRequest();
$controller = new RoutesController();

try {
    // Test getOfficers
    $response = $controller->getOfficers($mockRequest);
    echo "getOfficers response status: " . $response->getStatusCode() . PHP_EOL;
    echo "getOfficers response content: " . $response->getContent() . PHP_EOL;
} catch (Exception $e) {
    echo "Error in getOfficers: " . $e->getMessage() . PHP_EOL;
}

echo PHP_EOL . "=== Test 2: getOfficer Endpoint ===" . PHP_EOL;

try {
    // Test getOfficer
    $response = $controller->getOfficer(1);
    echo "getOfficer response status: " . $response->getStatusCode() . PHP_EOL;
    echo "getOfficer response content: " . $response->getContent() . PHP_EOL;
} catch (Exception $e) {
    echo "Error in getOfficer: " . $e->getMessage() . PHP_EOL;
}

echo PHP_EOL . "=== Test 3: Check JavaScript Console Errors ===" . PHP_EOL;
echo "To debug the actual issue:" . PHP_EOL;
echo "1. Open browser developer tools" . PHP_EOL;
echo "2. Go to Console tab" . PHP_EOL;
echo "3. Open User Maintenance modal" . PHP_EOL;
echo "4. Click Edit button and check for JavaScript errors" . PHP_EOL;
echo "5. Click Delete button and check for JavaScript errors" . PHP_EOL;
echo "6. Check Network tab for failed HTTP requests" . PHP_EOL;
