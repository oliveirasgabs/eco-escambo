<?php
  session_start();

  $usuario = $_POST["username"];
  $senha = $_POST["password"];

  if($senha == "patasdegalinha"){
    $_SESSION["logado"] = true;
    header("Location: index.php");
    exit();
  } else {
    header("Location: login.php?erro=1");
    exit();
  }

?>