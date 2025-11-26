<?php
session_start();
require './assets/src/config.php'; // Your PDO connection ($pdo)

// Only handle POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $role = $_POST['role'] ?? '';
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    // Basic validation
    if (!$role || !$username || !$password) {
        die('Please fill in all required fields.');
    }

    try {
        // Fetch user by username and role
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username AND category = :role");
        $stmt->execute(['username' => $username, 'role' => $role]);
        $user = $stmt->fetch();

            if (!$user) {
                    die("No user found with username '$username' and role '$role'");
                }

                if (!password_verify($password, $user['password'])) {
                    die("Password does not match for username '$username'");
                }
        if ($user && password_verify($password, $user['password'])) {
            // Login successful
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['category'];

            // Redirect based on role
            if ($user['category'] === 'customer') {
                header('Location: login-customer.php');
                exit;
            } else {
                header('Location: login-employee.php');
                exit;
            }
        } else {
            die('Invalid username, password, or role.');
        }

    } catch (PDOException $e) {
        die("Database error: " . $e->getMessage());
    }

} else {
    // Redirect if accessed directly
    header('Location: index.php');
    exit;
}
