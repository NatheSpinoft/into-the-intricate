<?php include 'functions.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DisplayForm</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <form method="POST">
        <h2>Enter Information</h2>
        
        <label for="fname">First Name:</label><br>
        <input type="text" id="fname" name="fname"><br><br>
        
        <label for="lname">Last Name:</label><br>
        <input type="text" id="lname" name="lname"><br><br>
        
        <input type="submit" value="Submit">
        <input type="submit" name="reset" value="Reset">

    </form>
    <div class="display">
           <?php displayFormData($displayData); ?>

    </div>
</body>
</html>
