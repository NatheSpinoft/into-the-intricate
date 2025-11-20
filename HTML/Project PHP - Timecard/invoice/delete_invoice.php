<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../index.php");
    exit();
}

require_once '../config/config.php';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $invoice_id = $_GET['id'];
    
    // First, verify that this invoice belongs to the logged-in user
    $stmt = $pdo->prepare("SELECT username FROM invoices00 WHERE id = :id");
    $stmt->execute([':id' => $invoice_id]);
    $invoice = $stmt->fetch();
    
    if ($invoice && $invoice['username'] === $_SESSION['username']) {
        // Delete the invoice
        $deleteStmt = $pdo->prepare("DELETE FROM invoices00 WHERE id = :id");
        $deleteStmt->execute([':id' => $invoice_id]);
        
        $_SESSION['message'] = "Invoice deleted successfully.";
        $_SESSION['message_type'] = "success";
    } else {
        $_SESSION['message'] = "Invoice not found or you don't have permission to delete it.";
        $_SESSION['message_type'] = "error";
    }
} else {
    $_SESSION['message'] = "Invalid invoice ID.";
    $_SESSION['message_type'] = "error";
}

header("Location: view_invoices.php");
exit();
?>