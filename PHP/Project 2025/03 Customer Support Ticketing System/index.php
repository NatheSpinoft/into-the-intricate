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

    <form action="login.php" method="POST">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" required>

        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>

        <button type="submit">Sign In</button>
    </form>
    </div>

</body>
</html>