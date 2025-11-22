<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mini Chirper</title>
    <link rel="stylesheet" href="./assets/css/styles-login.css">
    <link rel="icon" href="./assets/bird.png" type="image/png">
</head>
<body>
    <div class="login-container">
    <h1>Welcome to Mini Chirper</h1>
    <div class="login-box">
        <form action="login.php" method="POST">
            <label for="username">Username:</label><br>
            <input type="text" id="username" name="username" placeholder="John" required><br>
            <label for="password">Password:</label><br>
            <input type="password" id="password" name="password" required><br>
            <input type="submit" value="Login">
        </form>
        <?php if (!empty($error)) : ?>
        <p style="color:red;"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
        <p>Don't have an account? <a href="signup.php">Register here</a></p>
    </div>
    </div>
</body>
</html>