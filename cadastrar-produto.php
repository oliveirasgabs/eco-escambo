<?php
  session_start();
  if(!isset($_SESSION["logado"])){
    header("Location: login.php");
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="shortcut icon" href="/src/img/header/logo-eco-escambo.jpg">
  <title>Cadastrar novo produto - EcoEscambo</title>
  <link rel="stylesheet" href="./src/css/style-signup-product.css">
</head>
<body>
  <?php require_once("./src/pages/header/header.php"); ?>
  <div class="container-sign-up-product">
    <div class="sign-up-title"><h1>Cadastre o seu produto</h1></div>
    <div class="container-product-info">
      <form action="" method="post" enctype="multipart/form-data">
        <label for="product-title">Título breve</label>
        <input type="text" name="product-title" id="" placeholder="Nome do produto">
        <label for="product-description">Descrição</label>
        <textarea type="text" name="product-description" id="product-description" rows="4" placeholder="Descrição do seu produto, detalhes e o que você achar importante informar"></textarea>
        <label for="">Foto do produto</label>
        <input type="file" id="product-image" accept="image/*">
        <button class="cadastrar-produto" type="submit">Enviar cadastro</button>
      </form>
    </div>
  </div>

  <?php require_once("./src/pages/footer/footer.html"); ?>
</body>
</html>