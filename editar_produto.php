<?php
session_start();

// Verificação de sessão
if (!isset($_SESSION["logado"])) {
    header("Location: login.php");
    exit;
}

require_once './classes/db_connect.php';

// Inicialização de variáveis para verificação em formulário
$product = [
    'nome' => '',
    'descricao' => '',
    'foto' => ''
];

$errors = [];

if (isset($_GET['id'])) {
    $productId = $_GET['id'];
    $usuario_id = $_SESSION['usuario_id'];

    // Obtenção dos dados do produto do banco de dados
    $sql = "SELECT nome, descricao, foto FROM produtos WHERE id = :id AND usuario_id = :usuario_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $productId, ':usuario_id' => $usuario_id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$product) {
        echo "Produto não encontrado ou você não tem permissão para editá-lo.";
        exit;
    }
}

// Processamento do formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productId = $_POST['id'];
    $productName = $_POST['name'];
    $productDescription = $_POST['descricao'];
    $uploadDirectory = 'uploads/';

    if (empty($productName)) {
        $errors['name'] = "Por favor, preencha o nome do produto.";
    }
    if (empty($productDescription)) {
        $errors['descricao'] = "Por favor, preencha a descrição do produto.";
    }

    // Processamento do upload de imagem
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['foto']['tmp_name'];
        $fileName = $_FILES['foto']['name'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        // Gerar novo nome de arquivo único
        $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
        $dest_path = $uploadDirectory . $newFileName;

        // Movimentação do arquivo para o destino final
        if (move_uploaded_file($fileTmpPath, $dest_path)) {
            $uploadedFilePath = $dest_path;
        } else {
            $errors['foto'] = "Houve um erro ao mover o arquivo enviado.";
        }
    } else {
        $uploadedFilePath = null;
    }

    if (empty($errors)) {
        try {
            // Preparação da consulta SQL para atualização
            if ($uploadedFilePath) {
                $sql = "UPDATE produtos SET nome = ?, descricao = ?, foto = ? WHERE id = ?";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$productName, $productDescription, $uploadedFilePath, $productId]);
            } else {
                $sql = "UPDATE produtos SET nome = ?, descricao = ? WHERE id = ?";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$productName, $productDescription, $productId]);
            }
            header("location:meusprodutos.php");
            exit;
        } catch (PDOException $e) {
            echo "Erro ao atualizar produto: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Produto</title>
    <link rel="shortcut icon" href="./src/img/header/logo-eco-escambo.jpg">
    <link rel="stylesheet" href="./src/css/editarproduto.css">
</head>

<body>
    <?php require_once("./src/pages/header/header.php"); ?>
    <div class="container-principal">
        <h1>Editar Produto</h1>
        <form action="editar_produto.php?id=<?php echo $productId; ?>" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo $productId; ?>">
            <label for="name">Nome:</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($product['nome']); ?>"><br><br>
            <?php if (isset($errors['name'])) : ?>
                <span class="error"><?php echo $errors['name']; ?></span><br><br>
            <?php endif; ?>
            <label for="descricao">Descrição:</label>
            <textarea id="descricao" name="descricao"><?php echo htmlspecialchars($product['descricao']); ?></textarea><br><br>
            <?php if (isset($errors['descricao'])) : ?>
                <span class="error"><?php echo $errors['descricao']; ?></span><br><br>
            <?php endif; ?>
            <label for="foto">Imagem:</label>
            <input type="file" id="foto" name="foto"><br><br>
            <img src="<?php echo htmlspecialchars($product['foto']); ?>" alt="Imagem atual" style="width: 150px; height: auto;"><br><br>
            <?php if (isset($errors['foto'])) : ?>
                <span class="error"><?php echo $errors['foto']; ?></span><br><br>
            <?php endif; ?>
            <button type="submit">Salvar</button>
        </form>
    </div>
    <?php require_once("./src/pages/footer/footer.html"); ?>
</body>

</html>