<?php
session_start();
require_once './classes/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productId = $_POST['id'];
    $productName = $_POST['name'];
    $productDescription = $_POST['descricao'];
    $uploadDirectory = 'uploads/';

    // Processar upload de imagem
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['foto']['tmp_name'];
        $fileName = $_FILES['foto']['name'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
        $dest_path = $uploadDirectory . $newFileName;

        if (move_uploaded_file($fileTmpPath, $dest_path)) {
            $uploadedFilePath = $dest_path;
        } else {
            echo "Houve um erro ao mover o arquivo enviado.";
            exit();
        }
    } else {
        $uploadedFilePath = null;
    }

    // Atualizar os dados do produto no banco de dados
    try {
        if ($uploadedFilePath) {
            $sql = "UPDATE produtos SET nome = ?, descricao = ?, foto = ? WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$productName, $productDescription, $uploadedFilePath, $productId]);
        } else {
            $sql = "UPDATE produtos SET nome = ?, descricao = ? WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$productName, $productDescription, $productId]);
        }

        // Redireciona de volta para a página de produtos
        header("Location: meusprodutos.php");
        exit();
    } catch (PDOException $e) {
        echo "Erro ao atualizar produto: " . $e->getMessage();
    }
} else {
    echo "Método de requisição inválido.";
}
