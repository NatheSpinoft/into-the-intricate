<?php $time = date("H:i:s") ; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TimeForm</title>
    <link rel="stylesheet" href="styles.css">
    <script src="javascript.js" defer></script>
</head>
<body>
    <h1>Time</h1>
    <div id="clock"> <?php echo $time; ?></div>
    
</body>
</html>