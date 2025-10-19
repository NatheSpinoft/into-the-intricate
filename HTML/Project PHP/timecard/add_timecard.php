<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../index.php");
    exit();
}

require_once '../config/config.php'; // your PDO connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_SESSION['username'];
    $day = $_POST['day'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];

    // Validate times
    if (strtotime($end_time) <= strtotime($start_time)) {
        die("End time must be after start time.");
    }

    // Calculate hours worked
    $hours = (strtotime($end_time) - strtotime($start_time)) / 3600;

    // Use current timestamp for created_at
    $created_at = date('Y-m-d H:i:s');

    // Validate day
    $daysOfWeek = ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];
    if (!in_array($day, $daysOfWeek)) {
        die("Invalid day selected.");
    }

    // Insert into DB using PDO
    try {
        $stmt = $pdo->prepare("
            INSERT INTO timecards (username, day, start_time, end_time, hours, created_at) 
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([$username, $day, $start_time, $end_time, $hours, $created_at]);
    } catch (PDOException $e) {
        die("Database insert failed: " . $e->getMessage());
    }

    header("Location: timecard.php");
    exit();
}
