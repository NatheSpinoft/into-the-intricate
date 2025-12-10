<?php
session_start();
require '../assets/src/config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../--index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $user_id = $_SESSION['user_id'];
    $serial_number = trim($_POST['serial'] ?? '');
    $model = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $location = trim($_POST['location'] ?? '');
    $purchase_date = $_POST['purchase_date'] ?? '';
    $warranty_expiry = $_POST['warranty_date'] ?? '';
    $status = $_POST['status'] ?? '';
    $priority = $_POST['priority'] ?? ''; // Store this for potential use

    // Validate required fields
    if (empty($serial_number) || empty($model) || empty($description) || 
        empty($location) || empty($purchase_date) || empty($warranty_expiry) || empty($status)) {
        $_SESSION['error'] = 'Please fill in all required fields.';
        header('Location: ../login-customer.php');
        exit;
    }

    try {
        // Start transaction
        $pdo->beginTransaction();

        // First, check if printer with this serial number already exists
        $stmt = $pdo->prepare("SELECT printer_id FROM printers WHERE serial_number = :serial_number AND user_id = :user_id");
        $stmt->execute(['serial_number' => $serial_number, 'user_id' => $user_id]);
        $existing_printer = $stmt->fetch();

        if ($existing_printer) {
            // Printer exists, use its ID
            $printer_id = $existing_printer['printer_id'];
        } else {
            // Insert new printer
            $stmt = $pdo->prepare("
                INSERT INTO printers 
                (user_id, serial_number, model, description, location, purchase_date, warranty_expiry, status) 
                VALUES 
                (:user_id, :serial_number, :model, :description, :location, :purchase_date, :warranty_expiry, :status)
            ");

            $stmt->execute([
                'user_id' => $user_id,
                'serial_number' => $serial_number,
                'model' => $model,
                'description' => $description,
                'location' => $location,
                'purchase_date' => $purchase_date,
                'warranty_expiry' => $warranty_expiry,
                'status' => $status
            ]);

            $printer_id = $pdo->lastInsertId();
        }

        // Insert support ticket
        $stmt = $pdo->prepare("
            INSERT INTO printer_support 
            (printer_id, user_id, issue_description, status) 
            VALUES 
            (:printer_id, :user_id, :issue_description, :status)
        ");

        $stmt->execute([
            'printer_id' => $printer_id,
            'user_id' => $user_id,
            'issue_description' => $description,
            'status' => 'open' // Default status for new tickets
        ]);

        // Commit transaction
        $pdo->commit();

        $_SESSION['success'] = 'Ticket submitted successfully!';
        header('Location: ../login-customer.php');
        exit;

    } catch (PDOException $e) {
        // Rollback transaction on error
        $pdo->rollBack();
        $_SESSION['error'] = 'Database error: ' . $e->getMessage();
        header('Location: ../login-customer.php');
        exit;
    }

} else {
    // If not POST request, redirect back
    header('Location: ../login-customer.php');
    exit;
}
?>