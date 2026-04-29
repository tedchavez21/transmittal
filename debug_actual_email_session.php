<?php

echo "=== Debug Actual Email Session ===" . PHP_EOL;

echo PHP_EOL . "=== Debug Steps for Email Handler ===" . PHP_EOL;
echo "1. Check if user is actually logged in" . PHP_EOL;
echo "2. Check what emailUserName is in session" . PHP_EOL;
echo "3. Check if EmailHandler exists for that name" . PHP_EOL;
echo "4. Check if records exist for that encoder_id" . PHP_EOL;

echo PHP_EOL . "=== Browser Debug Steps ===" . PHP_EOL;
echo "1. Open browser developer tools (F12)" . PHP_EOL;
echo "2. Go to Application tab -> Session Storage" . PHP_EOL;
echo "3. Look for 'email_user_name' and 'email_logged_in'" . PHP_EOL;
echo "4. Check the exact value of 'email_user_name'" . PHP_EOL;
echo "5. Go to Console tab and check for JavaScript errors" . PHP_EOL;

echo PHP_EOL . "=== Common Issues After Encoder ID Implementation ===" . PHP_EOL;
echo "1. Session expired - user needs to login again" . PHP_EOL;
echo "2. Name mismatch - session name doesn't match EmailHandler.name exactly" . PHP_EOL;
echo "3. EmailHandler not found for the logged-in user" . PHP_EOL;
echo "4. encoder_id null for user's records" . PHP_EOL;

echo PHP_EOL . "=== Quick Fix Test ===" . PHP_EOL;
echo "To test if the issue is session-related:" . PHP_EOL;
echo "1. Logout from email handler" . PHP_EOL;
echo "2. Login again as 'Hanna Marie Lorica'" . PHP_EOL;
echo "3. Check if records appear" . PHP_EOL;

echo PHP_EOL . "=== If Still Not Working ===" . PHP_EOL;
echo "The issue might be:" . PHP_EOL;
echo "- Session not being maintained properly" . PHP_EOL;
echo "- Email handler login not setting session correctly" . PHP_EOL;
echo "- Name mismatch between session and database" . PHP_EOL;
echo "- Date filters interfering with query" . PHP_EOL;
