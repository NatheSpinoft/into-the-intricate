<?php
session_start();
if(isset($_SESSION['user_id'])){
    header("Location: timecard.php");
    exit();
}
// Redirect logged-in users
if(isset($_SESSION['user_id'])){
    if($_SESSION['role'] == 'admin'){
        header("Location: admin.php");
    } else {
        header("Location: timecard.php");
    }
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Welcome to Timecard System</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="sidebar">
    <h2>Timecard System</h2>
    <p>Track hours. Approve hours. Stay organized.</p>
</div>

<div class="main">
    <h2>Welcome!</h2>
    <p>Get started by logging in or creating a new account.</p>
    <div class="button-container">
        <a href="login.php" class="login-btn">Login</a>
        <a href="signup.php" class="signup-btn">Sign Up</a>
    </div>
</div>
</body>
</html>
