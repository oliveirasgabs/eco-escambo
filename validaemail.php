<?php

  session_start();

  $codigo_verificacao = mt_rand(100000, 999999); // Gera um número aleatório de 6 dígitos

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
  <link rel="stylesheet" href="/src/css/stylecadastro.css">
</head>
<body>
  <?php require_once("./src/pages/header/header.php"); ?>

  <div class="container--sign-up">
    Verifique a sua conta:
    <form method="post" action="valida-email.php">
      
      <label for="senha">Código de verificação:</label>
      <input type="numbers" id="verification-code" name="verification-code">
      <input type="submit" value="Entrar">
    </form>
  </div>
  <?php
      if(isset($_GET["erro"])){
        echo "<p>Código de verificação incorreto</p>";
      }
    ?>
  <br>
  <br>
  <?php
    echo $codigo_verificacao;
  ?>

  <?php require_once("./src/pages/footer/footer.html"); ?>
</body>
</html>