<?php 

    require 'PHPMailerAutoload.php';
    
    function contactMail($subject, $body, $Email, $name){
    
    $mail = new PHPMailer;
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->Port = 587;
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = 'tls';

    // Gmail credentials - replace with your actual Gmail credentials
    $mail->Username = 'your-email@gmail.com'; // Replace with your Gmail address
    $mail->Password = 'your-app-password'; // Replace with your Gmail app password

    $mail->setFrom("your-email@gmail.com", "O B S");
    $mail->addAddress("your-email@gmail.com"); // Where to send the contact form submissions
    $mail->addReplyTo($Email, $name); // Allow replying directly to the sender

    $mail->isHTML(true);
    $mail->Subject = "Contact Form: $subject";
    
    // Create a more professional email template
    $emailBody = "
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { background-color: #f8f9fa; padding: 15px; border-radius: 5px; margin-bottom: 20px; }
            .content { padding: 15px; background-color: #ffffff; border: 1px solid #e9ecef; border-radius: 5px; }
            .footer { margin-top: 20px; font-size: 12px; color: #6c757d; text-align: center; }
            .label { font-weight: bold; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h2>New Contact Form Submission</h2>
            </div>
            <div class='content'>
                <p><span class='label'>From:</span> $name</p>
                <p><span class='label'>Email:</span> $Email</p>
                <p><span class='label'>Subject:</span> $subject</p>
                <p><span class='label'>Message:</span></p>
                <p>" . nl2br($body) . "</p>
            </div>
            <div class='footer'>
                <p>This message was sent from the contact form on the Online Banking System website.</p>
            </div>
        </div>
    </body>
    </html>";
    
    $mail->Body = $emailBody;
    $mail->AltBody = "From: $name\nEmail: $Email\nSubject: $subject\n\nMessage:\n$body";

    try {
        if(!$mail->send()){
            // Log the error for debugging
            error_log("Mailer Error: " . $mail->ErrorInfo);
            return "fail";
        } else {
            return "success";
        }
    } catch (Exception $e) {
        // Log the exception for debugging
        error_log("Exception: " . $e->getMessage());
        return "fail";
    }
    }
?>
