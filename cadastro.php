<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cadastro - EcoEscambo</title>
  <link rel="stylesheet" href="/src/css/stylecadastro.css">
</head>

<body>
  <?php require_once("./src/pages/header/header.php"); ?>

  <div class="container--sign-up">
    <form method="post" action="validaemail.php">
      <label for="usuario">Nome Completo:</label>
      <input type="text" id="nomecompleto" name="name-complete">
      <label for="usuario">CPF:</label>
      <input type="text" id="cpf-brasileiro" name="register-number-cpf">
      <label for="usuario">Email:</label>
      <input type="text" id="user-email" name="user-email">
      <label for="usuario">Usuário:</label>
      <input type="text" id="usuario" name="username">
      <label for="senha">Senha:</label>
      <input type="password" id="senha" name="password">
      <input type="submit" value="Entrar">
    </form>
  </div>

  <?php require_once("./src/pages/footer/footer.html"); ?>
</body>

</html>