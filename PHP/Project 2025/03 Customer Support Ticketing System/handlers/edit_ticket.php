<?php
session_start();
require '../assets/src/config.php'; // PDO connection ($pdo)

// Make sure user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Accept POST only
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['ticket_id'])) {
    $_SESSION['error'] = "Ticket ID missing.";
    header('Location: ../my-tickets-edit.php');
    exit;
}

$ticket_id = (int)$_POST['ticket_id'];
$issue_description = trim($_POST['issue_description']);
$status = $_POST['status'];

// Validate status
if (!in_array($status, ['open', 'maintenance', 'closed'])) {
    $_SESSION['error'] = "Invalid status.";
    header('Location: ../my-tickets-edit.php');
    exit;
}

// Set resolved_at if closing
$resolved_at = ($status === 'closed') ? date('Y-m-d H:i:s') : null;

// Update ticket
$update = "
    UPDATE printer_support
    SET issue_description = ?, status = ?, resolved_at = ?
    WHERE ticket_id = ? AND user_id = ?
";

$stmt = $pdo->prepare($update);
$success = $stmt->execute([$issue_description, $status, $resolved_at, $ticket_id, $user_id]);

if ($success) {
    $_SESSION['success'] = "Ticket #$ticket_id updated successfully!";
} else {
    $_SESSION['error'] = "Failed to update ticket #$ticket_id.";
}

// Redirect back to edit page
header('Location: ../my-tickets-edit.php');
exit;
