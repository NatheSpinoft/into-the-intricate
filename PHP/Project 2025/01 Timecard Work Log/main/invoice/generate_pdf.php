<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../index.php");
    exit();
}

require_once '../config/config.php';

// Get invoice ID from URL
$invoice_id = $_GET['id'] ?? null;

if (!$invoice_id) {
    die("Invoice ID is required.");
}

// Fetch invoice details
$stmt = $pdo->prepare("
    SELECT * FROM invoices00 
    WHERE id = :id AND username = :username
");
$stmt->execute([
    ':id' => $invoice_id,
    ':username' => $_SESSION['username']
]);
$invoice = $stmt->fetch();

if (!$invoice) {
    die("Invoice not found or you don't have permission to view it.");
}

// Fetch invoice items
$stmt = $pdo->prepare("
    SELECT * FROM invoice_items 
    WHERE invoice_id = :invoice_id
");
$stmt->execute([':invoice_id' => $invoice_id]);
$items = $stmt->fetchAll();

// Download FPDF if you don't have it yet
// Get it from: http://www.fpdf.org/en/download.php
// Place fpdf.php in your project root or libs folder

require_once('../libs/fpdf/fpdf.php');

// Create PDF
$pdf = new FPDF();
$pdf->AddPage();

// Header - Company Info
$pdf->SetFont('Arial', 'B', 20);
$pdf->Cell(0, 10, 'INVOICE', 0, 1, 'C');
$pdf->Ln(5);

// Invoice Info Box
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(95, 6, 'Invoice #: ' . $invoice['id'], 0, 0);
$pdf->Cell(95, 6, 'Date: ' . date('M d, Y', strtotime($invoice['invoice_date'])), 0, 1, 'R');
$pdf->Ln(3);

// Bill To Section
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 6, 'Bill To:', 0, 1);
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(0, 6, $invoice['company'], 0, 1);
$pdf->Ln(5);

// Table Header
$pdf->SetFillColor(240, 240, 240);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(80, 8, 'Description', 1, 0, 'L', true);
$pdf->Cell(20, 8, 'Qty', 1, 0, 'C', true);
$pdf->Cell(25, 8, 'Price', 1, 0, 'R', true);
$pdf->Cell(20, 8, 'Tax', 1, 0, 'C', true);
$pdf->Cell(30, 8, 'Total', 1, 1, 'R', true);

// Table Rows
$pdf->SetFont('Arial', '', 10);
foreach ($items as $item) {
    $pdf->Cell(80, 7, substr($item['description'], 0, 40), 1, 0, 'L');
    $pdf->Cell(20, 7, $item['qty'], 1, 0, 'C');
    $pdf->Cell(25, 7, '$' . number_format($item['price'], 2), 1, 0, 'R');
    $pdf->Cell(20, 7, $item['tax_type'], 1, 0, 'C');
    $pdf->Cell(30, 7, '$' . number_format($item['total'], 2), 1, 1, 'R');
}

// Grand Total
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(145, 8, 'Grand Total:', 1, 0, 'R');
$pdf->Cell(30, 8, '$' . number_format($invoice['grand_total'], 2), 1, 1, 'R');

$pdf->Ln(10);

// Payment Info
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(0, 6, 'Payment Method: ' . $invoice['payment_method'], 0, 1);
if ($invoice['card_last4']) {
    $pdf->Cell(0, 6, 'Card: **** **** **** ' . $invoice['card_last4'], 0, 1);
}

$pdf->Ln(10);

// Footer
$pdf->SetFont('Arial', 'I', 9);
$pdf->Cell(0, 6, 'Thank you for your business!', 0, 1, 'C');
$pdf->Cell(0, 6, 'Generated on: ' . date('Y-m-d H:i:s'), 0, 1, 'C');

// Output PDF
$filename = 'Invoice_' . $invoice['id'] . '_' . date('Ymd') . '.pdf';
$pdf->Output('D', $filename); // 'D' = download, 'I' = display in browser
?>