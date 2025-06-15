<?php
include "user/connection.php";

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Test contact form submission
$test_name = "Test User";
$test_email = "test@example.com";
$test_subject = "Test Contact Form";
$test_message = "This is a test message to verify the contact form functionality.";

echo "Testing contact form submission...<br>";

// Check database connection
if (!$conn) {
    echo "Database connection failed: " . mysqli_connect_error() . "<br>";
    exit;
} else {
    echo "Database connection successful!<br>";
}

// Insert into database
$query = "INSERT INTO contact_messages (name, email, subject, message) VALUES ('$test_name', '$test_email', '$test_subject', '$test_message')";
echo "Executing query: $query<br>";

$result = mysqli_query($conn, $query);

if ($result) {
    echo "Contact form submission successful! Message stored in database.<br>";
    
    // Get the ID of the inserted record
    $id = mysqli_insert_id($conn);
    echo "Inserted record ID: $id<br>";
    
    // Verify the record was inserted correctly
    $verify_query = "SELECT * FROM contact_messages WHERE id = $id";
    echo "Verifying with query: $verify_query<br>";
    
    $verify_result = mysqli_query($conn, $verify_query);
    
    if ($verify_result && mysqli_num_rows($verify_result) > 0) {
        $row = mysqli_fetch_assoc($verify_result);
        echo "Verified record in database:<br>";
        echo "Name: " . $row['name'] . "<br>";
        echo "Email: " . $row['email'] . "<br>";
        echo "Subject: " . $row['subject'] . "<br>";
        echo "Message: " . $row['message'] . "<br>";
        
        // Delete the test record
        $delete_query = "DELETE FROM contact_messages WHERE id = $id";
        echo "Deleting with query: $delete_query<br>";
        
        if (mysqli_query($conn, $delete_query)) {
            echo "Test record deleted successfully!<br>";
        } else {
            echo "Error deleting test record: " . mysqli_error($conn) . "<br>";
        }
    } else {
        echo "Error verifying record: " . mysqli_error($conn) . "<br>";
    }
} else {
    echo "Error inserting test record: " . mysqli_error($conn) . "<br>";
}

// Close the connection
mysqli_close($conn);
?> 