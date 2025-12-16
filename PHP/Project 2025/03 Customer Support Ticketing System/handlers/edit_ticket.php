<?php
// db.php should create a $pdo PDO connection
require 'db.php';

if (!isset($_GET['ticket_id'])) {
    die("Ticket ID missing.");
}

$ticket_id = (int)$_GET['ticket_id'];

/*
 Fetch ticket + printer details
*/
$sql = "
SELECT 
    ps.ticket_id,
    ps.issue_description,
    ps.status,
    ps.created_at,
    ps.resolved_at,
    p.model,
    p.serial_number,
    p.location
FROM printer_support ps
JOIN printers p ON ps.printer_id = p.printer_id
WHERE ps.ticket_id = ?
";

$stmt = $pdo->prepare($sql);
$stmt->execute([$ticket_id]);
$ticket = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$ticket) {
    die("Ticket not found.");
}

/*
 Handle form submission
*/
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $issue_description = $_POST['issue_description'];
    $status = $_POST['status'];

    // Set resolved_at only if closing ticket
    if ($status === 'closed') {
        $resolved_at = date('Y-m-d H:i:s');
    } else {
        $resolved_at = null;
    }

    $update = "
        UPDATE printer_support
        SET issue_description = ?,
            status = ?,
            resolved_at = ?
        WHERE ticket_id = ?
    ";

    $stmt = $pdo->prepare($update);
    $stmt->execute([
        $issue_description,
        $status,
        $resolved_at,
        $ticket_id
    ]);

    header("Location: view_ticket.php?ticket_id=" . $ticket_id);
    exit;
}
?>
