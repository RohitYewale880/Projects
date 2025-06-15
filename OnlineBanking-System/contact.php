<?php 
    include "mail/contactMail.php";
    include "user/connection.php";

    $response = "fail";
    
    if(isset($_POST['name']) && isset($_POST['email']) && isset($_POST['subject']) && isset($_POST['message'])){
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $subject = mysqli_real_escape_string($conn, $_POST['subject']);
        $message = mysqli_real_escape_string($conn, $_POST['message']);
        
        // Validate inputs
        if(empty($name) || empty($email) || empty($subject) || empty($message)) {
            $response = "fail";
            error_log("Contact form validation failed: Empty fields");
        } else {
            // Store in database
            $query = "INSERT INTO contact_messages (name, email, subject, message) VALUES ('$name', '$email', '$subject', '$message')";
            $result = mysqli_query($conn, $query);
            
            if($result) {
                // Try to send email, but don't fail if email sending fails
                $emailResult = contactMail($subject, $message, $email, $name);
                
                // Return success if database insertion was successful, regardless of email result
                $response = "success";
                
                if($emailResult === "fail") {
                    error_log("Email sending failed for contact form submission from: $email");
                }
            } else {
                $response = "fail";
                error_log("Database insertion failed: " . mysqli_error($conn));
            }
        }
    } else {
        error_log("Contact form submission missing required fields");
    }
    
    echo $response;
?>