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
  
</head>
<body>
  <div class="header">
      <img src="assets/android.svg">
      <h1>The Fancy Title</h1>
  </div>

  <div class="menu-selection">
      <ul>
          <li><a href="index.php">Home</a></li>
          <li><a href="#">Projects</a></li>
          <li><a href="blog.php">Blogs</a></li>
          <li><a href="about.php">About</a></li>
      </ul>
  </div>

  <div class="container">
    
    <div class="column">
        <?php include './phpfiles/Blogs-medium.php'
        ?>
    </div>

    <div class="column blog-content">
        <iframe id="blogFrame" name="blogFrame" class="blog-frame" src="blogs/blog-home.html"></iframe>
    </div>

    <div class="column project-category">
        <h2>Web Development</h2>
    <div class="project-card">
        <p><strong>Portfolio Website</strong></p>
        <p>Create a personal portfolio using HTML, CSS, JS.</p>
    </div>
    <div class="project-card">
        <p><strong>To-Do App</strong></p>
        <p>Build a simple task manager with local storage.</p>
    </div>

    </div>
    <div class="column">Column 5</div>
    <div class="column">Column 6</div>
    <div class="column project-category">
          <h2>AI / ML</h2>
    <div class="project-card">
        <strong>Image Classifier</strong>
        <p>Train a small ML model to categorize images.</p>
    </div>
    <div class="project-card">
        <strong>Chatbot</strong>
        <p>Simple AI chatbot using Python and NLTK.</p>
    </div>
    </div>
    <div class="column">Column 8</div>
    <div class="column">
        <?php
        renderWeatherColumn($weather);
        ?>
    </div>

    <div class="column">
        <?php include './phpfiles/social-media.php'; ?>
    </div>
  </div>

  <script>
    function resizeIframe() {
      const iframe = document.getElementById('blogFrame');
      const doc = iframe.contentDocument || iframe.contentWindow.document;
      if (doc) {
          const minHeight = 300;
          const maxHeight = 1500; // adjust if content is very long
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
