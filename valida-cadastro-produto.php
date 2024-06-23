<?php
session_start();
if (!isset($_SESSION["logado"])) {
    header("Location: login.php");
    exit();
}

require './classes/db_connect.php';

// Verifica se os dados foram enviados via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Exibir os dados enviados para depuração
    echo "<pre>";
    print_r($_POST);
    print_r($_FILES);
    echo "</pre>";

    // Sanitiza e valida os dados do formulário
    $titulo = filter_input(INPUT_POST, 'product-title', FILTER_SANITIZE_STRING);
    $descricao = filter_input(INPUT_POST, 'product-description', FILTER_SANITIZE_STRING);
    $foto = $_FILES['product-image'];

    if (empty($titulo) || empty($descricao) || empty($foto['name'])) {
        // Se algum campo estiver vazio, exibe mensagem de erro
        echo "Erro: Todos os campos são obrigatórios.";
        exit();
    }

    // Diretório de destino para as fotos dos produtos
    $diretorio_destino = './uploads/produtos/';
    $caminho_arquivo = $diretorio_destino . basename($foto['name']);
    $extensao_arquivo = strtolower(pathinfo($caminho_arquivo, PATHINFO_EXTENSION));

    // Verifica se o arquivo é uma imagem válida
    $tipos_permitidos = array('jpg', 'jpeg', 'png', 'gif');
    if (!in_array($extensao_arquivo, $tipos_permitidos)) {
        echo "Erro: Tipo de arquivo não permitido.";
        exit();
    }

    // Move o arquivo carregado para o diretório de destino
    if (!move_uploaded_file($foto['tmp_name'], $caminho_arquivo)) {
        echo "Erro: Falha ao mover o arquivo enviado.";
        exit();
    }

    // Prepara a declaração SQL para inserir o produto no banco de dados
    try {
        $sql = "INSERT INTO produtos (usuario_id, nome, descricao, foto) VALUES (:usuario_id, :nome, :descricao, :foto)";
        $stmt = $pdo->prepare($sql);
        
        // Verifica se o usuário está na sessão
        if (!isset($_SESSION['usuario_id'])) {
            echo "Erro: ID do usuário não encontrado na sessão.";
            exit();
        }
        
        $stmt->bindParam(':usuario_id', $_SESSION['usuario_id'], PDO::PARAM_INT);
        $stmt->bindParam(':nome', $titulo);
        $stmt->bindParam(':descricao', $descricao);
        $stmt->bindParam(':foto', $caminho_arquivo);
        $stmt->execute();

        // Redireciona para a página de meus produtos após o cadastro
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