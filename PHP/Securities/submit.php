<?php
// Simple spam & captcha check

// Honeypot check
if(!empty($_POST['website'])){
    die('Spam detected.');
}

// Sanitize inputs
$name = htmlspecialchars($_POST['name']);
$email = htmlspecialchars($_POST['email']);
$message = htmlspecialchars($_POST['message']);

// Here you could also log messages or send email
// For demo, just show confirmation
echo "<h2>Thank you, $name!</h2>";
echo "<p>Your message has been received.</p>";
?>
