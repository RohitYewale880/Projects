<?php
include "user/connection.php";

// Test database connection
if ($conn) {
    echo "Database connection successful!<br>";
    
    // Test query to check if contact_messages table exists
    $query = "SHOW TABLES LIKE 'contact_messages'";
    $result = mysqli_query($conn, $query);
    
    if (mysqli_num_rows($result) > 0) {
        echo "contact_messages table exists!<br>";
        
        // Test inserting a record
        $test_name = "Test User";
        $test_email = "test@example.com";
        $test_subject = "Test Subject";
        $test_message = "This is a test message";
        
        $insert_query = "INSERT INTO contact_messages (name, email, subject, message) 
                         VALUES ('$test_name', '$test_email', '$test_subject', '$test_message')";
        
        if (mysqli_query($conn, $insert_query)) {
            echo "Test record inserted successfully!<br>";
            
            // Get the ID of the inserted record
            $id = mysqli_insert_id($conn);
            
            // Delete the test record
            $delete_query = "DELETE FROM contact_messages WHERE id = $id";
            if (mysqli_query($conn, $delete_query)) {
                echo "Test record deleted successfully!<br>";
            } else {
                echo "Error deleting test record: " . mysqli_error($conn) . "<br>";
            }
        } else {
            echo "Error inserting test record: " . mysqli_error($conn) . "<br>";
        }
    } else {
        echo "contact_messages table does not exist!<br>";
    }
} else {
    echo "Database connection failed: " . mysqli_connect_error() . "<br>";
}

// Close the connection
mysqli_close($conn);
?> 