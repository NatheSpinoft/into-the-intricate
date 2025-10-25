<?php
$config = include 'config.php'; // Load the API key
include 'weather.php';

// Pass BOTH arguments: city name and config
$weather = getWeather("Ottawa,CA", $config); 
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>The Fancy Title</title>
  
  <!-- Styles -->
  <link rel="stylesheet" href="assets/css/header.css">
  <link rel="stylesheet" href="assets/css/menu-selection.css">
  <link rel="stylesheet" href="assets/css/darkness.css">
  <link rel="stylesheet" href="assets/css/columns.css">
  <link rel="stylesheet" href="assets/css/social-media.css">
  <link rel="stylesheet" href="blogs/blog.css">

  <style>
    body {
        margin: 0px;
    }

    
  </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <img src="assets/icons/android.svg" alt="Logo">
        <h1>The Fancy Title</h1>
    </div>

    <!-- Navigation Menu -->
    <div class="menu-selection">
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="#">Projects</a></li>
            <li><a href="blog.php">Blogs</a></li>
            <li><a href="about.php">About</a></li>
        </ul>
    </div>

    <!-- Main Container -->
    <div class="container">

        <!-- Blog Links Column -->
        <div class="column">
            <?php include './phpfiles/Blogs-medium.php'
            ?>
        </div>
        <!-- Blog Content Column -->
        <div class="column blog-content">
            <iframe id="blogFrame" name="blogFrame" class="blog-frame" src="blogs/blog-home.php"></iframe>
        </div>

        <!-- Weather Column -->
        <div class="column">
            <?php
            renderWeatherColumn($weather);
            ?>
        </div>

        <div class="column">
        </div>
        <div class="column">
        </div>



        <!-- Social Media Column -->
        <div class="column">
            <?php include './phpfiles/social-media.php' ?>
        </div>
    </div> <!-- End Container -->
    <!-- Scripts -->
    <script>
        function resizeIframe() {
            const iframe = document.getElementById('blogFrame');
            const doc = iframe.contentDocument || iframe.contentWindow.document;
            if (doc) {
                const minHeight = 300;
                const maxHeight = 1500;
                const newHeight = Math.min(maxHeight, Math.max(minHeight, doc.body.scrollHeight));
                iframe.style.height = newHeight + 'px';
            }
        }

        const iframe = document.getElementById('blogFrame');
        iframe.addEventListener('load', resizeIframe);
        window.addEventListener('resize', resizeIframe);
    </script>
</body>
</html>