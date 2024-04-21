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
  <link rel="shortcut icon" href="/src/img/header/logo-eco-escambo.jpg">
</head>

<body>
  <?php require_once("./src/pages/header/header.php"); ?>

  <div class="container--sign-up">

    <h1>Cadastre-se abaixo para fazer suas trocas! </h1>
    <div class="container--info-sign-up">
      <form method="post" action="validaemail.php">
        <div class="container--labels">
          <div><label for="usuario">Nome Completo:</label></div>
          <div><input type="text" id="nomecompleto" name="name-complete" placeholder="Sem abreviar"></div>
          <div><label for="usuario">CPF:</label></div>
          <div><input type="text" id="cpf-brasileiro" name="register-number-cpf" placeholder="Seu CPF sem hífen ou ponto"></div>
          <div><label for="usuario">Email:</label></div>
          <div><input type="text" id="user-email" name="user-email" placeholder="Seu email"></div>
          <div><label for="senha">Senha:</label></div>
          <div><input type="password" id="senha" name="password" placeholder=" Sua senha secreta"></div>
          <div><label for="senha">Repita a senha:</label></div>
          <div><input type="password" id="confirmar_senha" name="confirm-password" placeholder="Digite certinho"></div>
        </div>
        <br>
        <br>
        <span class="submmit-buttom"><button type="button" onclick="verificarSenhas()">Verificar Senhas</button></span>
        <br>
        <br>
        <button id="btnCadastrar" type="submit" value="Entrar" style="display: none;">Conclua o seu cadastro</button>
      </form>
    </div>
  </div>

  <?php require_once("./src/pages/footer/footer.html"); ?>
</body>

<script>
    function verificarSenhas() {
        var senha = document.getElementById("senha").value;
        var confirmarSenha = document.getElementById("confirmar_senha").value;

        if (senha === confirmarSenha) {
            btnCadastrar.style.display = "block";
        } else {
            btnCadastrar.style.display = "none";
            alert("Erro: As senhas não coincidem.");
        }
    }
</script>

</html>