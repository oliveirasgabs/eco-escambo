<?php
  session_start();
  require './classes/db_connect.php';

  //Tratando os dados do form

  $nomeCompleto = filter_input(INPUT_POST, 'name-complete', FILTER_SANITIZE_STRING);
  $cpf = filter_input(INPUT_POST, 'register-number-cpf', FILTER_SANITIZE_STRING);
  $email = filter_input(INPUT_POST, 'user-email', FILTER_SANITIZE_EMAIL);
  $senha = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
  $confirmarSenha = filter_input(INPUT_POST, 'confirm-password', FILTER_SANITIZE_STRING);

  if (empty($nomeCompleto) || empty($cpf) || empty($email) || empty($senha) || empty($confirmarSenha)) {
    header("Location: cadastro.php?erro=campos_vazios");
    exit();
  }

  if (strlen($senha) < 6) {
    header("Location: cadastro.php?erro=senha_curta");
    exit();
}

if (!preg_match('/^(?=.*[A-Z])(?=.*\d)[A-Za-z\d]{6,}$/', $senha)) {
  header("Location: cadastro.php?erro=senha_invalida");
  exit();
}
  
  //Fazendo uma segunda verificação de senhas
  if($senha !== $confirmarSenha){
    header("Location: cadastro.php?erro=senhas_nao_coincidem");
    exit();
  }

  //Verificando email
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header("Location: cadastro.php?erro=email_invalido");
    exit();
  }

  $_SESSION["user_email"] = $email;
  $_SESSION["user_name"] = $nomeCompleto;

  try{
    $sql = "INSERT INTO usuarios (cpf, nome, email, senha, conta_verificada) VALUES (:cpf, :nome, :email, :senha, 0)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':cpf', $cpf);
    $stmt->bindParam(':nome', $nomeCompleto);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':senha', $senha);

    $stmt->execute();

    header("Location: ./valida-contareal.php");
    exit();

  } catch (PDOException $e) {
    // Tratamento de erro
    header("Location: cadastro.php?erro=inesperado");
    die("Erro ao cadastrar usuário: " . $e->getMessage());
    exit();
    
}
  
?>