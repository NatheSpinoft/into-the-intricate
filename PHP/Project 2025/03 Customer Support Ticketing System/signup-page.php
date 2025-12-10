<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Relational Management System - Sign Up</title>
    <link rel="stylesheet" href="./assets/css/styles-index.css">
</head>
<body>
    <div class="login-container">
        <h2>Sign Up</h2>

        <form action="handlers/signup.php" method="POST">
            <label for="role">Register as:</label>
            <select id="role" name="role" required>
                <option value="" disabled selected>Select role</option>
                <option value="customer">Customer</option>
                <option value="employee">Employee</option>
            </select>

            <label for="username">Username</label>
            <input type="text" id="username" name="username" required>

            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>

            <label for="confirm_password">Confirm Password</label>
            <input type="password" id="confirm_password" name="confirm_password" required>

            <button type="submit">Sign Up</button>
        </form>

        <p>Already have an account? <a href="--index.php">Login here</a></p>
    </div>
</body>
</html>
