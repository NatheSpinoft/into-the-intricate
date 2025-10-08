<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $guests = $_POST['guests'];

    // Set cookies for first and last name
    setcookie("fname", $fname, time() + (90 * 24 * 60 * 60), "/");
    setcookie("lname", $lname, time() + (90 * 24 * 60 * 60), "/");

    // Send emails (example, simple mail)
    $toBusiness = "stefannstuff@yahoo.com";
    $subjectBusiness = "New Reservation from $fname $lname";
    $messageBusiness = "Reservation Details:\nName: $fname $lname\nEmail: $email\nPhone: $phone\nDate: $date\nTime: $time\nGuests: $guests";
    mail($toBusiness, $subjectBusiness, $messageBusiness);

    $subjectUser = "Reservation Confirmation";
    $messageUser = "Hello $fname,\n\nYour reservation for $date at $time has been received.\nThank you!";
    mail($email, $subjectUser, $messageUser);

    // Redirect back to reserve.php with success flag
    header("Location: reserve.php?success=1");
    exit();
}
?>
