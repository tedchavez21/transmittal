<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Test session isolation
session_start();

echo "=== Session Contamination Test ===" . PHP_EOL;
echo "Current session data: " . json_encode($_SESSION) . PHP_EOL;
echo "Expected: Only data for logged-in user should be present" . PHP_EOL;

// Check if there's cross-contamination between channels
$emailData = isset($_SESSION['email_user_name']) ? $_SESSION['email_user_name'] : 'null';
$facebookData = isset($_SESSION['facebook_user']) ? $_SESSION['facebook_user'] : 'null';
$officerData = isset($_SESSION['officer_name']) ? $_SESSION['officer_name'] : 'null';

echo "Email channel: " . $emailData . PHP_EOL;
echo "Facebook channel: " . $facebookData . PHP_EOL;
echo "Officer channel: " . $officerData . PHP_EOL;
echo PHP_EOL;

if ($emailData && ($facebookData || $officerData)) {
    echo "CONTAMINATION: Multiple channels have session data" . PHP_EOL;
} elseif ($emailData) {
    echo "OK: Only Email channel has session data" . PHP_EOL;
} elseif ($facebookData) {
    echo "OK: Only Facebook channel has session data" . PHP_EOL;
} elseif ($officerData) {
    echo "OK: Only Officer channel has session data" . PHP_EOL;
} else {
    echo "OK: No channel has session data" . PHP_EOL;
}

echo "=== Test Complete ===" . PHP_EOL;
?>
