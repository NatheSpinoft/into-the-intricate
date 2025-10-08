<?php
$fname = isset($_COOKIE['fname']) ? $_COOKIE['fname'] : '';
$lname = isset($_COOKIE['lname']) ? $_COOKIE['lname'] : '';
$showMessage = isset($_GET['success']) ? true : false;
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu: The Fancy Restaurant</title>
    <link rel="stylesheet" href="reserve.css">
</head>
<body>
    <header>
        <h1>The Fancy Restaurant</h1>
        
    <nav>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="menu.html">Menu</a></li>
            <li><a href="#">About</a></li>
            <li><a href="reserve.php">Reserve</a></li>

        </ul>
    </nav>
        </header>
    <main>
        <div class="reservation">
            <h2>Make A Reservation</h2>
            <?php if ($showMessage): ?>
    <p class="success-message">Your reservation has been received! An email has been sent to you and the restaurant.</p>
            <?php endif; ?>

            <form action="process_reservation.php" method="post">
                <label for="fname">First Name:</label> <br>
                <input type="text" id="fname" name="fname" value="<?php echo htmlspecialchars($fname); ?>" required> <br><br>

                <label for="lname">Last Name:</label> <br>
                <input type="text" id="lname" name="lname" value="<?php echo htmlspecialchars($lname); ?>" required> <br><br>

                <label for="email">Email:</label> <br>
                <input type="email" id="email" name="email" required> <br><br>

                <label for="phone">Phone Number:</label> <br>
                <input type="tel" id="phone" name="phone" required> <br><br>

                <label for="date">Reservation Date:</label> <br>
                <input type="date" id="date" name="date" required> <br><br>

                <label for="time">Reservation Time:</label> <br>
                <input type="time" id="time" name="time" required> <br><br>

                <label for="guests">Number of Guests:</label> <br>
                <input type="number" id="guests" name="guests" min="1" max="20" required> <br><br>

                <input type="submit" value="Reserve Now">
        </div>
    </main>

</body>
</html>