<?php
session_start();
if (!isset($_SESSION["logado"])) {
    header("Location: login.php");
    exit();
}

require './classes/db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titulo = filter_input(INPUT_POST, 'product-title', FILTER_SANITIZE_STRING);
    $descricao = filter_input(INPUT_POST, 'product-description', FILTER_SANITIZE_STRING);
    $foto = $_FILES['product-image'];

    if (empty($titulo) || empty($descricao) || empty($foto['name'])) {
        header("Location: cadastrar-produto.php?erro=1");
        exit();
    }

    $diretorio_destino = './uploads/produtos/';
    if (!is_dir($diretorio_destino)) {
        mkdir($diretorio_destino, 0777, true);
    }

    $caminho_arquivo = $diretorio_destino . basename($foto['name']);
    $extensao_arquivo = strtolower(pathinfo($caminho_arquivo, PATHINFO_EXTENSION));

    $tipos_permitidos = array('jpg', 'jpeg', 'png', 'gif');
    if (!in_array($extensao_arquivo, $tipos_permitidos)) {
        header("Location: cadastrar-produto.php?erro=2");
        exit();
    }

    if (!move_uploaded_file($foto['tmp_name'], $caminho_arquivo)) {
        header("Location: cadastrar-produto.php?erro=3");
        exit();
    }

    try {
        $sql = "INSERT INTO produtos (usuario_id, nome, descricao, foto) VALUES (:usuario_id, :nome, :descricao, :foto)";
        $stmt = $pdo->prepare($sql);

        if (!isset($_SESSION['usuario_id'])) {
            header("Location: cadastrar-produto.php?erro=4");
            exit();
        }

        $stmt->bindParam(':usuario_id', $_SESSION['usuario_id'], PDO::PARAM_INT);
        $stmt->bindParam(':nome', $titulo);
        $stmt->bindParam(':descricao', $descricao);
        $stmt->bindParam(':foto', $caminho_arquivo);
        $stmt->execute();

        header("Location: meusprodutos.php");
        exit();
    } catch (PDOException $e) {
        die("Erro ao inserir produto no banco de dados: " . $e->getMessage());
    }
} else {
    header("Location: cadastrar-produto.php");
    exit();
}
?>