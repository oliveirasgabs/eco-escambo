<?php

session_start();

/* $codigoVerificacao = $_POST["codigo_verificacao"]; */

if (isset($_POST['verification-code']) && $_POST['verification-code'] == $_SESSION['codigo_verificacao']) {
  $_SESSION["logado"] = true;
  header("Location: index.php");
  exit();
} else {
  header("Location: validaemail.php?erro=1");
  exit();
}

?>