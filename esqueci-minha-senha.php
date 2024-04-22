<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Esqueci a minha senha - EcoEscambo</title>
  <link rel="stylesheet" href="/src/css/styleesquecisenha.css">
</head>

<body>
  <?php require_once("./src/pages/header/header.php"); ?>

  <div class="container--content-forgot-password">
    <div>
      <h1>Esqueci a minha senha</h1>
      <p>Digite o e-mail usado para cadastrar a sua conta:</p>
    </div>
    <div>
      <form action="validaemail.php" method="post">
        <div class="space"><label for="">E-mail cadastrado</label></div>
        <div><input type="text" name="user-email"></div>
        <button class="botao-recuperar" type="submit">Recuperar acesso</button>
      </form>
    </div>
  </div>

  <?php require_once("./src/pages/footer/footer.html"); ?>
</body>

</html>