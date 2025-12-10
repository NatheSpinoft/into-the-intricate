<?php
session_start();
$error = $_SESSION['error'] ?? '';
$success = $_SESSION['success'] ?? '';
unset($_SESSION['error']);
unset($_SESSION['success']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Support System</title>
    <link rel="stylesheet" href="./assets/css/styles-customer.css">
</head>
<body>
    <header>
        <h1 id="header-title">Customer Support System</h1>
        <form action="./handlers/logout.php" method="POST">
            <button id="logout-button" type="submit">Logout</button>
        </form>
    </header>
    <nav>
    <ul>
        <li><a href="#">Dashboard</a></li>
        <li><a href="#">My Tickets</a></li>
        <li><a href="submit-ticket.php">Submit Ticket</a></li>
        <li><a href="#">Account Settings</a></li>
    </ul>
    </nav>
    <main>
        <?php if (!empty($error)): ?>
    <p style="color:red; padding:10px; background-color:#ffe6e6; border-radius:5px; margin-bottom:15px;">
        <?php echo htmlspecialchars($error); ?>
    </p>
<?php endif; ?>

<?php if (!empty($success)): ?>
    <p style="color:green; padding:10px; background-color:#e6ffe6; border-radius:5px; margin-bottom:15px;">
        <?php echo htmlspecialchars($success); ?>
    </p>
<?php endif; ?>
        <h2>Welcome, Customer!</h2>
        <p>This is your dashboard where you can manage your support tickets and view your account details.</p>
    </main>
    <footer>
        <p>&copy; 2024 Customer Support System. All rights reserved.</p>
    </footer>
    
</body>
</html>