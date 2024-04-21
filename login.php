<?php
  session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - EcoEscambo</title>
  <link rel="stylesheet" href="/src/css/stylelogin.css">
</head>
<body>
  <?php require_once("./src/pages/header/header.php"); ?>

  <div class="container--login-info">
    <form method="post" action="valida-user.php">
      <label for="usuario">Usuário:</label>
      <input type="text" id="usuario" name="username">
      <label for="senha">Senha:</label>
      <input type="password" id="senha" name="password">
      <input type="submit" value="Entrar">
    </form>
  </div>

  <?php
      if(isset($_GET["erro"])){
        echo "<p>Senha e/ou usuário incorreto(s).</p>";
      }
    ?>

  <?php require_once("./src/pages/footer/footer.html"); ?>
</body>
</html>