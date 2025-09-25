<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect form data safely
    $data = [
        'first_name' => htmlspecialchars($_POST['fname']),
        'last_name' => htmlspecialchars($_POST['lname']),
        'subject' => htmlspecialchars($_POST['subject']),
        'message' => htmlspecialchars($_POST['message']),
        'timestamp' => date('Y-m-d H:i:s')
    ];

    // Path to your JSON file
    $file = 'messages.json';

    // Load existing messages
    $messages = file_exists($file) ? json_decode(file_get_contents($file), true) : [];

    // Add new message
    $messages[] = $data;

    // Save back to file
    if (file_put_contents($file, json_encode($messages, JSON_PRETTY_PRINT))) {
        // Redirect to success page
        header("Location: success.php");
        exit;
    } else {
        // Redirect to failure page
        header("Location: failed.php");
        exit;
    }
} else {
    // Redirect if accessed without POST
    header("Location: failed.php");
    exit;
}
?>
