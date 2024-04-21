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
    <div class="container--form">
      <?php
        if(isset($_GET["erro"])){
          echo "<p>Senha e/ou usuário incorreto(s).</p>";
        }
      ?>
      <form method="post" action="valida-user.php">
        <label for="usuario">Usuário</label>
        <br>
        <input type="text" id="usuario" name="username" placeholder="Digite seu usuário">
        <br>
        <label for="senha">Senha</label>
        <br>
        <input type="password" id="senha" name="password" placeholder="Digite sua senha">
        <br>
        <input type="submit" value="Entrar">
      </form>
      <div><a href="esqueci-minha-senha.php">Esqueci a minha senha</a> | <a href="">Esqueci o meu usuário</a></div>
    </div>
  </div>

  <?php require_once("./src/pages/footer/footer.html"); ?>
</body>
</html>