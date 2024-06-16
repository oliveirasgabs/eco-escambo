<?php
session_start();
require './classes/db_connect.php';

$usuario = $_POST["username"];
$senha = $_POST["password"];

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
            $_SESSION["logado"] = true;
            $_SESSION["user"] = $user['nome'];
            header("Location: index.php");
            exit();
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