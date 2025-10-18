<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

$mysqli = new mysqli("localhost", "db_user", "db_password", "db_name");
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

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

    // Calculate current week's Sunday
    $today = date('Y-m-d');
    $dayOfWeek = date('w', strtotime($today));
    $sunday = date('Y-m-d', strtotime("-{$dayOfWeek} days", strtotime($today)));

    // Map day to offset
    $daysOfWeek = ['Sunday'=>0,'Monday'=>1,'Tuesday'=>2,'Wednesday'=>3,'Thursday'=>4,'Friday'=>5,'Saturday'=>6];
    if (!array_key_exists($day, $daysOfWeek)) {
        die("Invalid day selected.");
    }

    $date = date('Y-m-d', strtotime("+".$daysOfWeek[$day]." days", strtotime($sunday)));

    // Insert into DB
    $stmt = $mysqli->prepare("INSERT INTO timecards (username, day, date, start_time, end_time, hours) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssd", $username, $day, $date, $start_time, $end_time, $hours);

    if (!$stmt->execute()) {
        die("Database insert failed: " . $stmt->error);
    }

    $stmt->close();
    $mysqli->close();

    header("Location: timecard.php");
    exit();
}
?>
