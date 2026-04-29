<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Test authentication directly
$username = 'teddy';
$wrongPassword = 'wrongpassword';
$correctPassword = 'teddy123';

$officer = App\Models\Officer::where('username', $username)->first();

echo "=== Authentication Test ===" . PHP_EOL;
echo "Username: " . $officer->username . PHP_EOL;
echo "Name: " . $officer->name . PHP_EOL;
echo "Password Hash: " . $officer->password . PHP_EOL;
echo PHP_EOL;

echo "Password Verification Tests:" . PHP_EOL;
echo "Hash check 'wrongpassword': " . (Hash::check($wrongPassword, $officer->password) ? 'TRUE' : 'FALSE') . PHP_EOL;
echo "Hash check 'teddy123': " . (Hash::check($correctPassword, $officer->password) ? 'TRUE' : 'FALSE') . PHP_EOL;
echo PHP_EOL;

echo "Login Logic Results:" . PHP_EOL;
echo "Should login with wrong password: " . (!Hash::check($wrongPassword, $officer->password) ? 'FALSE' : 'TRUE') . PHP_EOL;
echo "Should login with correct password: " . (!Hash::check($correctPassword, $officer->password) ? 'FALSE' : 'TRUE') . PHP_EOL;
echo PHP_EOL;

if (!Hash::check($wrongPassword, $officer->password)) {
    echo "RESULT: Wrong password correctly REJECTED" . PHP_EOL;
} else {
    echo "RESULT: Wrong password incorrectly ACCEPTED - SECURITY ISSUE!" . PHP_EOL;
}

if (!Hash::check($correctPassword, $officer->password)) {
    echo "RESULT: Correct password incorrectly REJECTED" . PHP_EOL;
} else {
    echo "RESULT: Correct password correctly ACCEPTED" . PHP_EOL;
}
?>
