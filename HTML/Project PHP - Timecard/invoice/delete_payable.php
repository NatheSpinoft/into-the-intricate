<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../index.php");
    exit();
}

require_once '../config/config.php';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $payable_id = $_GET['id'];
    
    // First, verify that this payable belongs to the logged-in user
    $stmt = $pdo->prepare("SELECT username FROM payables WHERE id = :id");
    $stmt->execute([':id' => $payable_id]);
    $payable = $stmt->fetch();
    
    if ($payable && $payable['username'] === $_SESSION['username']) {
        // Begin transaction to delete payable and its items
        $pdo->beginTransaction();
        
        try {
            // Delete associated items first
            $deleteItemsStmt = $pdo->prepare("DELETE FROM payable_items WHERE payable_id = :id");
            $deleteItemsStmt->execute([':id' => $payable_id]);
            
            // Then delete the payable
            $deleteStmt = $pdo->prepare("DELETE FROM payables WHERE id = :id");
            $deleteStmt->execute([':id' => $payable_id]);
            
            // Commit transaction
            $pdo->commit();
            
            $_SESSION['message'] = "Bill/Expense deleted successfully.";
            $_SESSION['message_type'] = "success";
        } catch (Exception $e) {
            // Rollback on error
            $pdo->rollBack();
            $_SESSION['message'] = "Error deleting bill/expense.";
            $_SESSION['message_type'] = "error";
        }
    } else {
        $_SESSION['message'] = "Bill/Expense not found or you don't have permission to delete it.";
        $_SESSION['message_type'] = "error";
    }
} else {
    $_SESSION['message'] = "Invalid bill/expense ID.";
    $_SESSION['message_type'] = "error";
}

header("Location: view_payables.php");
exit();
?>