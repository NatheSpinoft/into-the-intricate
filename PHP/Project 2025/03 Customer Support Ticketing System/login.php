<?php
session_start();
require './assets/src/config.php'; // PDO connection ($pdo)

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $role = $_POST['role'] ?? '';
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!$role || !$username || !$password) {
        $_SESSION['error'] = 'Please fill in all required fields.';
        header('Location: index.php');
        exit;
    } else {
        try {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username AND category = :role");
            $stmt->execute(['username' => $username, 'role' => $role]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                if (password_verify($password, $user['password'])) {
                    // Login successful
                    $_SESSION['user_id'] = $user['user_id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['role'] = $user['category'];

                    // Redirect based on role
                    if ($user['category'] === 'customer') {
                        header('Location: login-customer.php');
                    } else {
                        header('Location: login-employee.php');
                    }
                    exit;
                } else {
                    $_SESSION['error'] = 'Password does not match.';
                    header('Location: --index.php');
                    exit;
                }
            } else {
                $_SESSION['error'] = 'No user found with that username and role.';
                header('Location: --index.php');
                exit;
            }

        } catch (PDOException $e) {
            $_SESSION['error'] = 'Database error: ' . $e->getMessage();
            header('Location: --index.php');
            exit;
        }
    }
} else {
    header('Location: --index.php');
    exit;
}
?>