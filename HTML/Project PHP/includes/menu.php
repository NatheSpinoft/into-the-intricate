<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu: HOME PAGE</title>
    <link rel="stylesheet" href="../assets/css/reset.css">
    <link rel="stylesheet" href="../assets/css/header.css">
    <link rel="stylesheet" href="../assets/css/layout.css">


</head>
<body>
    <header>
        <div class="head-container">
            <div class="welcome">
                <h1>Welcome: <?php echo htmlspecialchars($_SESSION['username']); ?></h1>
            </div>
            <div class="button-group">
                <a href="menu.php">HOME</a>
                <a href="logout.php">LOG OUT</a>
            </div>
        </div>
    </header>
    <div class="container">
        <div class="sidenav">
            <ul>
                <li><a href="../timecard/timecard.php">Time</a></li>
                <li><a href="invoices.php">Invoices</a></li>
                <li><a href="payables.php">Payables</a></li>
            </ul>
        </div>
        <div class="main">
            <h1>Announcements</h1>
            <p>No new announcements at this time.</p>
        </div>
    </div>
</body>
</html>