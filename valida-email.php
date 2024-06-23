<?php

session_start();

require './classes/db_connect.php';

/* $codigoVerificacao = $_POST["codigo_verificacao"]; */

if (isset($_POST['verification-code']) && $_POST['verification-code'] == $_SESSION['codigo_verificacao']) {

  $email = $_SESSION['user_email'];
  $sql = "UPDATE usuarios SET conta_verificada = 1 WHERE email = :email";
  $stmt = $pdo->prepare($sql);
  $stmt->bindParam(':email', $email);
  $stmt->execute();

  $sql = "SELECT id, nome FROM usuarios WHERE email = :email";
  $stmt = $pdo->prepare($sql);
  $stmt->bindParam(':email', $email);
  $stmt->execute();
  $user = $stmt->fetch(PDO::FETCH_ASSOC);

  $_SESSION["logado"] = true;
  $_SESSION['usuario_id'] = $user['id'];
  $_SESSION['user'] = $user['nome'];
        
  header("Location: index.php");

  exit();
  
} else {
  header("Location: valida-contareal.php?erro=1");
  exit();
}

?>