<?php
require 'db/database.php';

$username = 'password';
$password = 'password'; // plain text password
$role = 'admin';

// Hash the password
$hashed_password = password_hash($password, PASSWORD_BCRYPT);

// Insert into database
$stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
$stmt->execute([$username, $hashed_password, $role]);

echo "Admin user created successfully.";
