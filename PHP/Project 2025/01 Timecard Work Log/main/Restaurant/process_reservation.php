<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include PHPMailer
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

// Load local config (contains SMTP credentials)
if (!file_exists(__DIR__ . '/config.local.php')) {
    die('Missing config.local.php — create it with your SMTP credentials.');
}
require __DIR__ . '/config.local.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // -------------------- Sanitize inputs --------------------
    $fname = htmlspecialchars(trim($_POST['fname']));
    $lname = htmlspecialchars(trim($_POST['lname']));
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $phone = preg_replace('/[^0-9\+\-\(\)\s]/', '', $_POST['phone']);
    $date = $_POST['date'];
    $time = $_POST['time'];
    $guests = (int)$_POST['guests'];

    // -------------------- Validate email --------------------
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email address.");
    }

    // -------------------- Set cookies --------------------
    setcookie("fname", $fname, time() + (90 * 24 * 60 * 60), "/");
    setcookie("lname", $lname, time() + (90 * 24 * 60 * 60), "/");

    // -------------------- Prepare PHPMailer --------------------
    $mail = new PHPMailer(true);

    try {
        // SMTP configuration
        $mail->isSMTP();
        $mail->Host       = SMTP_HOST;
        $mail->SMTPAuth   = true;
        $mail->Username   = SMTP_USER;
        $mail->Password   = SMTP_PASS;
        $mail->SMTPSecure = 'tls';
        $mail->Port       = SMTP_PORT;

        // Debugging logged to file (won’t break headers)
        $mail->SMTPDebug = 2;
        $mail->Debugoutput = function($str, $level) {
            file_put_contents(__DIR__.'/mail_debug.log', date('Y-m-d H:i:s') . " [$level] $str\n", FILE_APPEND);
        };

        $mail->setFrom(SMTP_FROM, SMTP_FROM_NAME);
        $mail->isHTML(true);

        // -------------------- Email to restaurant --------------------
        $mail->clearAllRecipients();
        $mail->addAddress('stefannstuff@yahoo.com'); // Restaurant email
        $mail->Subject = "New Reservation from $fname $lname";
        $mail->Body = "
            <h3>New Reservation</h3>
            <p><strong>Name:</strong> $fname $lname</p>
            <p><strong>Email:</strong> $email</p>
            <p><strong>Phone:</strong> $phone</p>
            <p><strong>Date:</strong> $date</p>
            <p><strong>Time:</strong> $time</p>
            <p><strong>Guests:</strong> $guests</p>
        ";
        $mail->send();

        // -------------------- Email to customer --------------------
        $mail->clearAllRecipients();
        $mail->addAddress($email); // Customer email
        $mail->Subject = "Reservation Confirmation";
        $mail->Body = "
            <p>Hello $fname,</p>
            <p>Your reservation for <strong>$date at $time</strong> has been received. Thank you!</p>
            <p>We look forward to seeing you at The Fancy Restaurant.</p>
        ";
        $mail->send();

        // -------------------- Redirect on success --------------------
        header("Location: reserve.php?success=1");
        exit();

    } catch (Exception $e) {
        // Log PHPMailer errors to a file
        file_put_contents(__DIR__ . '/mail_errors.log', date('Y-m-d H:i:s') . " - {$mail->ErrorInfo}\n", FILE_APPEND);
        die("Sorry, we could not send your reservation. Please try again later.");
    }
}
?>
