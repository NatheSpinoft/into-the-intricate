<?php
session_start();
$error = $_SESSION['error'] ?? '';
unset($_SESSION['error']); // Clear the error after retrieving it
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer relational management system</title>
    <link rel="stylesheet" href="./assets/css/styles-index.css">
</head>
<body>
    <div class="login-container">
    <h2>Login</h2>

    <form action="handlers/login.php" method="POST">
        <label for="role">Login as:</label>
        <select id="role" name="role" required>
            <option value="" disabled selected>Select role</option>
            <option value="customer">Customer</option>
            <option value="employee">Employee</option>
        </select>
        
        <label for="username">Username</label>
        <input type="text" id="username" name="username" required>

        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>

        <button type="submit">Sign In</button>
        
        <!-- Display error message here -->
        <?php if (!empty($error)): ?>
            <p style="color:red; margin-top:10px;"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>

        <p>Register <a href="signup-page.php">here</a></p>
    </form>
    </div>

</body>
</html>