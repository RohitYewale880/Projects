$(document).ready(function () {
    $("#submit").click(function (e) {
        e.preventDefault();
        
        // Get form values
        let name = $("#name").val().trim();
        let email = $("#email").val().trim();
        let subject = $("#subject").val().trim();
        let message = $("#message").val().trim();
        
        // Reset previous error states
        $("#error-message").attr("hidden", true);
        $("#success-message").attr("hidden", true);
        
        // Validate form fields
        if (!name) {
            showError("Please enter your name");
            return;
        }
        
        if (!email) {
            showError("Please enter your email");
            return;
        }
        
        if (!isValidEmail(email)) {
            showError("Please enter a valid email address");
            return;
        }
        
        if (!subject) {
            showError("Please enter a subject");
            return;
        }
        
        if (!message) {
            showError("Please enter your message");
            return;
        }
        
        // Show sending status
        $("#status").text('Sending message...').attr('hidden', false);
        
        // Submit form via AJAX
        $.ajax({
            type: "POST",
            url: "contact.php",
            data: {
                name: name,
                email: email,
                subject: subject,
                message: message
            },
            success: function (response) {
                $("#status").attr('hidden', true);
                
                console.log("Server response:", response); // Debug log
                
                if (response === "success") {
                    // Clear form
                    $("#name, #email, #subject, #message").val("");
                    showSuccess("Message sent successfully! We will get back to you soon.");
                } else {
                    showError("Failed to send message. Please try again or contact us directly at info@onlinebanking.info");
                    console.error("Form submission failed with response:", response); // Debug log
                }
            },
            error: function(xhr, status, error) {
                $("#status").attr('hidden', true);
                showError("An error occurred. Please try again later or contact us directly at info@onlinebanking.info");
                console.error("AJAX error:", status, error); // Debug log
                console.error("Response:", xhr.responseText); // Debug log
            }
        });
    });
    
    // Helper function to validate email
    function isValidEmail(email) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    }
    
    // Helper function to show error message
    function showError(message) {
        $("#error-message").text(message).attr("hidden", false);
        setTimeout(function() {
            $("#error-message").attr("hidden", true);
        }, 5000); // Increased timeout for better visibility
    }
    
    // Helper function to show success message
    function showSuccess(message) {
        $("#success-message").text(message).attr("hidden", false);
        setTimeout(function() {
            $("#success-message").attr("hidden", true);
        }, 5000); // Increased timeout for better visibility
    }
});