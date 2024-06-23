<?php

session_start();

$user_email = $_SESSION["user_email"];
$user_name = $_SESSION["user_name"];

$codigo_verificacao = mt_rand(100000, 999999); // Gera um número aleatório de 6 dígitos

$errormessenger = "<p class='error-messenger'>Código de verificação não coincide.</p>";

/*   $email_destino = $_POST["user-email"];
  $assunto = 'Código de Verificação';
  $mensagem = 'Seu código de verificação é: ' . $codigo_verificacao;
  $headers = 'From: gabbeolivsantos@gmail.com';

  mail($email_destino, $assunto, $mensagem, $headers); */
$_SESSION['codigo_verificacao'] = $codigo_verificacao;

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Validar Conta - EcoEscambo</title>
  <link rel="stylesheet" href="./src/css/stylevalidaemail.css">
</head>

<body>
  <?php require_once("./src/pages/header/header.php"); ?>

  <div class="container--sign-up">
    <div class="container--client-info">
      <p>Olá, <?php echo $user_name ;?>!</p>
      <p>Enviamos um email com um código de verificação para <?php echo $user_email; ?></p>
      <p>Digite o código recebido no campo abaixo para verificar a sua conta:</p>
    </div>
    <div class="error-messenger"><?php
      if (isset($_GET["erro"])) {
        echo $errormessenger;
      }
      ?>
    </div>
    <form method="post" action="valida-email.php">
      <div class="container-verification-code">
        <div><label for="senha">Código de verificação:</label></div>
        <div><input type="numbers" id="verification-code" name="verification-code" placeholder="Digite o código aqui">
        </div>
        <div><button class="submit-buttom" type="submit" value="Entrar">Validar</button></div>
      </div>
    </form>
  </div>
  <br>
  <br>
  <?php
  echo $codigo_verificacao;
  ?>

  <?php require_once("./src/pages/footer/footer.html"); ?>
</body>

</html>