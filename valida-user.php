<?php
session_start();
require './classes/db_connect.php';

$usuario = filter_input(INPUT_POST, 'user-email', FILTER_SANITIZE_EMAIL);
$senha = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

try {
    // Prepara a declaração SQL para selecionar o usuário
    $sql = "SELECT * FROM usuarios WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':email', $usuario);
    $stmt->execute();

    // Verifica se encontrou o usuário
    if ($stmt->rowCount() == 1) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($senha === $user['senha']) {
            // Verifica se a conta está verificada
            if ($user['conta_verificada'] == 1) {
                $_SESSION["logado"] = true;
                $_SESSION['usuario_id'] = $user['id'];
                $_SESSION['user'] = $user['nome'];
                header("Location: index.php");
                exit();
            } else {
                // Conta não verificada, redireciona para a página de validação
                $_SESSION["user_name"] = $user['nome'];
                $_SESSION["user_email"] = $user['email'];
                header("Location: valida-contareal.php");
                exit();
            }
        } else {
            // Senha incorreta
            header("Location: login.php?erro=1");
            exit();
        }
    } else {
        // Email não encontrado
        header("Location: login.php?erro=1");
        exit();
    }
} catch (PDOException $e) {
    // Tratamento de erro
    die("Erro ao conectar com o banco de dados: " . $e->getMessage());
}
?>