<?php
session_start();
require './assets/src/config.php'; // your database connection file

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    die("You must be logged in to post a chirp.");
}

// Check if form submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['chirp'])) {
    $chirp = trim($_POST['chirp']);
    $user_id = $_SESSION['user_id'];

    // Prepare and insert
    $stmt = $conn->prepare("INSERT INTO chirps (user_id, chirp) VALUES (?, ?)");
    $stmt->bind_param("is", $user_id, $chirp);
    
    if ($stmt->execute()) {
        header("Location: chirper.php"); // redirect back to chirp feed
        exit();
    } else {
        echo "Error posting chirp: " . $stmt->error;
    }
    $stmt->close();
}
?>
