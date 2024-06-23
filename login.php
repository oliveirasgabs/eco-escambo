<?php
session_start();

$errormessenger = "<p class='error-messenger'>Senha e/ou usuário incorreto(s).</p>";
require './classes/db_connect.php'
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - EcoEscambo</title>
  <link rel="stylesheet" href="./src/css/stylelogin.css">
  <link rel="shortcut icon" href="./src/img/header/logo-eco-escambo.jpg">
</head>

<body>
  <?php require_once("./src/pages/header/header.php"); ?>

  <div class="container--login-info">
    <h1>Acesse a sua conta:</h1>
    <div class="container--form">
      <?php
      if (isset($_GET["erro"])) {
        echo $errormessenger;
      }
      ?>
      <form method="post" action="valida-user.php">
        <label for="usuario">Email</label>
        <br>
        <input type="text" id="usuario" name="user-email" placeholder="Digite seu email cadastrado">
        <br>
        <label for="senha">Senha</label>
        <br>
        <input type="password" id="senha" name="password" placeholder="Digite sua senha">
        <br>
        <span class="submmit-bottom"><input type="submit" value="Acessar"></span>
      </form>
      <div><a href="esqueci-minha-senha.php">Esqueceu a sua senha?</a> | <a href="cadastro.php">Ainda não é usuário?</a>
      </div>
    </div>
  </div>

  <?php require_once("./src/pages/footer/footer.html"); ?>
</body>

</html>