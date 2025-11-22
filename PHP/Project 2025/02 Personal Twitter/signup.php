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
        <label for="Username">Username:</label>
        <input type="text" id="username" name="username" placeholder="John" required>
        <label for="Email">Email:</label>
        <input type="email" id="email" name="email" placeholder="John@company.com" required>
        <label for="Password">Password:</label>
        <input type="password" id="password" name="password" required>
        <label for="ConfirmPassword">Confirm Password:</label>
        <input type="password" id="confirm_password" name="confirm_password" required>
        <input type="submit" value="Sign Up">
        <p>Already have an account? <a href="index.php">Login here</a></p>
        </div>
    </div>


</body>
</html>