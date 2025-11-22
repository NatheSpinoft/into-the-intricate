<?php
session_start();
require './assets/src/config.php'; // include the PDO connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if ($username && $password) {
        $stmt = $pdo->prepare("SELECT id, username, password_hash FROM users WHERE username = :username");
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password_hash'])) {
            // Login successful
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header('Location: chirper.php'); // redirect to main page
            exit;
        } else {
            $error = "Invalid username or password";
        }
    } else {
        $error = "Please enter username and password";
    }
}
?>
