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
  <link rel="stylesheet" href="header.css">
  <link rel="stylesheet" href="menu-selection.css">
  <link rel="stylesheet" href="darkness.css">
  <link rel="stylesheet" href="columns.css">
  <link rel="stylesheet" href="social-media.css">
  <link rel="stylesheet" href="blogs/blog.css">
  <link rel="stylesheet" href="contact-page.css">

  <style>
    body {
        margin: 0px;
    }

    
  </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <img src="assets/android.svg" alt="Logo">
        <h1>The Fancy Title</h1>
    </div>

    <!-- Navigation Menu -->
    <div class="menu-selection">
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="#">Projects</a></li>
            <li><a href="blog.php">Blogs</a></li>
            <li><a href="#">About</a></li>
        </ul>
    </div>

    <!-- Main Container -->
    <div class="container">
        <div class="column contact-form">
            <div class="project-card">
            <h1>About Us</h1>
            <p>
            This is a site that includes all projects and blogs of the associate organization to display the knowledge acquired
            during the year 2025. As times get tougher and technology is becoming more prevalent it is wise to start a portfolio
            of what is being learnt and gradually meet up to where technology is.
            </p>
            </div>
        </div>
        <div class="column contact-form">
            <div class="project-card">
                <fieldset>
                <legend><h1>Contact Form</h1></legend>
                <div class="name-row">
                    <div class="row-box">
                        <label for="fname">First Name:</label>
                        <input type="text" id="fname" name="fname">
                    </div>
                    <div class="row-box">
                        <label for="fname">Last Name:</label>
                        <input type="text" id="lname" name="lname">
                    </div>
                </div>
                    <label for="subject">Subject:</label>
                    <input type="text" id="subject" name="subject" class="subject-input">
                    <label for="message">Message:</label>
                    <textarea id="message" name="message" rows="5" cols="50"></textarea>
                    <div id="message-count">Characters remaining: 5000</div>
                    
                    <button>Submit</button>
                </fieldset>

            </div>
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
<script src="message-script.js"></script>
</body>
</html>