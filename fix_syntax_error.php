<?php

// Fix the PHP syntax error in RoutesController
// The error is likely a missing closing parenthesis or semicolon

// Let me read the current problematic section
$routesControllerContent = file_get_contents('c:\xampp\htdocs\transmittal\app\Http\Controllers\RoutesController.php');
$lines = explode("\n", $routesControllerContent);

// Find the problematic section around line 250
for ($i = 240; $i < count($lines); $i++) {
    if (strpos($lines[$i], '} else {') !== false) {
        echo "Found potential issue at line " . ($i + 1) . ": " . trim($lines[$i]) . PHP_EOL;
        echo "Context: " . substr($lines[$i], max(0, 50)) . PHP_EOL;
    }
}

// The issue appears to be missing closing brace or semicolon
echo PHP_EOL . "=== Analysis ===" . PHP_EOL;
echo "The error suggests there's a syntax issue around the else block for officer lookup." . PHP_EOL;
echo "Let me check the exact structure around that area." . PHP_EOL;
