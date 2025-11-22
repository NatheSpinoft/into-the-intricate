<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mini Chirper</title>
    <link rel="stylesheet" href="./assets/css/styles-chirper.css">
    <link rel="icon" href="./assets/bird.png" type="image/png">
</head>
<body>
    <div class="chirp-container">
        <div class="chirp-thread">
            <h2>Chirp Thread</h2>
            <?php
                // Include the file that fetches and displays chirps
                //include 'fetch_chirps.php';
            ?>
        <div class="chirp-box">
        <form action="post_chirp.php" method="POST">
            <label for="chirp">What's on your mind?</label><br>
            <textarea id="chirp" name="chirp" rows="4" cols="50" maxlength="280" required></textarea><br>
            <input type="submit" value="Chirp">
        </form>
        </div>
    </div>
    
</body>
</html>