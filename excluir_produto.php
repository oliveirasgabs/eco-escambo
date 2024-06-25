<?php
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION["logado"])) {
    header("Location: login.php");
    exit;
}

$usuario_id = $_SESSION['usuario_id'];


require_once './classes/db_connect.php';

if (isset($_GET['id'])) {
    $productId = $_GET['id'];

    // Verifica se o produto existe e pertence ao usuário atual
    $sql = "SELECT id FROM produtos WHERE id = :id AND usuario_id = :usuario_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $productId, ':usuario_id' => $usuario_id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($product) {
        // Deletar o produto do banco de dados
        $sql = "DELETE FROM produtos WHERE id = :id";
        $stmt = $pdo->prepare($sql);

        if ($stmt->execute([':id' => $productId])) {
            // Redirecionar de volta para a página de produtos
            header("Location: meusprodutos.php");
            exit();
        } else {
            echo "Erro ao excluir produto.";
        }
    } else {
        echo "Produto não encontrado ou você não tem permissão para excluí-lo.";
    }
} else {
    echo "ID do produto não especificado.";
}
