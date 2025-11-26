<?php
session_start();
require './assets/src/config.php'; // PDO connection ($pdo)

// Only process POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Get form inputs and sanitize
    $role = $_POST['role'] ?? '';
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Basic validation
    if (!$role || !$username || !$email || !$password || !$confirm_password) {
        die('Please fill in all required fields.');
    }

    if ($password !== $confirm_password) {
        die('Passwords do not match.');
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die('Invalid email address.');
    }

    try {
        // Check if username or email already exists
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = :username OR email = :email");
        $stmt->execute(['username' => $username, 'email' => $email]);
        if ($stmt->fetchColumn() > 0) {
            die('Username or email already exists.');
        }

        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insert new user using PDO
        $insert = $pdo->prepare("
            INSERT INTO users (username, password, category, email)
            VALUES (:username, :password, :category, :email)
        ");
        $insert->execute([
            ':username' => $username,
            ':password' => $hashedPassword,
            ':category' => $role,
            ':email' => $email
        ]);

        // Optionally log in user immediately
        $_SESSION['username'] = $username;
        $_SESSION['role'] = $role;

        // Redirect based on role
        if ($role === 'customer') {
            header('Location: login-customer.php');
            exit;
        } else {
            header('Location: login-employee.php');
            exit;
        }

    } catch (PDOException $e) {
        die("Database error: " . $e->getMessage());
    }

} else {
    // Redirect if accessed directly
    header('Location: signup-page.php');
    exit;
}
