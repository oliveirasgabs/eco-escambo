<?php
session_start();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Eco Escambo</title>
  <link rel="stylesheet"
    href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@48,400,0,0" />
  <link rel="shortcut icon" href="/src/img/header/logo-eco-escambo.jpg">
  <link rel="stylesheet" href="/src/css/style.css">
  <script src="./src//js//carousel.js" defer></script>
  <script src="./src//js//carouselbanner.js" defer></script>

</head>

<body>
  <?php require_once("./src/pages/header/header.php"); ?>
  <div class="container">
    <div class="banner">
      <div class="slidebanner active">
        <img src="/src//img//banner0.png">
      </div>
      <div class="slidebanner">
        <img src="/src//img//banner1.png">
      </div>
      <div class="slidebanner">
        <img src="/src//img//banner2.png">
      </div>
      <div class="navigation">
        <div class="btn active"></div>
        <div class="btn"></div>
        <div class="btn"></div>
      </div>
    </div>

    <div class="slider-titulo">
      <h2>Sugestões que podem te interessar</h2>
    </div>
    <div class="container-body">
      <div class="slider-container">
        <div class="slider-box">
          <button id="prev-slide" class="slide-button material-symbols-rounded">
            chevron_left
          </button>
          <ul class="image-list">
            <img src="/src//img//produtos//1.webp" alt="img-1" class="image-item">
            <img src="/src//img//produtos//2.png" alt="img-2" class="image-item">
            <img src="/src//img//produtos//3.avif" alt="img-3" class="image-item">
            <img src="/src//img//produtos//4.webp" alt="img-4" class="image-item">
            <img src="/src//img//produtos//applewatch.jpg" alt="img-5" class="image-item">
            <img src="/src//img//produtos//cafeteira.jpg" alt="img-6" class="image-item">
            <img src="/src//img//produtos//canon.jpg" alt="img-7" class="image-item">
          </ul>
          <button id="next-slide" class="slide-button material-symbols-rounded">
            chevron_right
          </button>
        </div>
        <div class="slider-scrollbar">
          <div class="scrollbar-track">
            <div class="scrollbar-thumb"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php require_once("./src/pages/footer/footer.html"); ?>

</body>

</html>