<?php
session_start();
require './assets/src/config.php'; // PDO connection

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Basic validation
    if (!$username || !$email || !$password || !$confirm_password) {
        $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        // Check if username or email already exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = :username OR email = :email");
        $stmt->execute(['username' => $username, 'email' => $email]);
        if ($stmt->fetch()) {
            $error = "Username or email already exists.";
        } else {
            // Hash password
            $password_hash = password_hash($password, PASSWORD_DEFAULT);

            // Insert user
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password_hash) VALUES (:username, :email, :password_hash)");
            $stmt->execute([
                'username' => $username,
                'email' => $email,
                'password_hash' => $password_hash
            ]);

            $success = "Account created successfully! You can now <a href='index.php'>login</a>.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mini Chirper</title>
    <link rel="stylesheet" href="./assets/css/styles-signup.css">
    <link rel="icon" href="./assets/bird.png" type="image/png">
</head>
<body>
    <div class="signup-container">
    <h1>Create Your Mini Chirper Account</h1>
        <div class="signup-box">
            <?php if ($error): ?>
                <p style="color:red;"><?php echo htmlspecialchars($error); ?></p>
            <?php elseif ($success): ?>
                <p style="color:green;"><?php echo $success; ?></p>
            <?php endif; ?>
        <form action="" method="POST">
        <label for="Username">Username:</label>
        <input type="text" id="username" name="username" placeholder="John" required>
        <label for="Email">Email:</label>
        <input type="email" id="email" name="email" placeholder="John@company.com" required>
        <label for="Password">Password:</label>
        <input type="password" id="password" name="password" required>
        <label for="ConfirmPassword">Confirm Password:</label>
        <input type="password" id="confirm_password" name="confirm_password" required>
        <input type="submit" value="Sign Up">
        </form>
        <p>Already have an account? <a href="index.php">Login here</a></p>
        </div>
    </div>


</body>
</html>