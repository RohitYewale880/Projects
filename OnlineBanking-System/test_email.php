<?php
include "mail/contactMail.php";

// Test email sending
$test_name = "Test User";
$test_email = "test@example.com";
$test_subject = "Test Email";
$test_message = "This is a test email to verify the email functionality.";

echo "Attempting to send test email...<br>";

$result = contactMail($test_subject, $test_message, $test_email, $test_name);

if ($result === "success") {
    echo "Test email sent successfully!<br>";
} else {
    echo "Failed to send test email. Check the error logs for details.<br>";
    echo "Make sure you've updated the email credentials in mail/contactMail.php<br>";
}
?> 