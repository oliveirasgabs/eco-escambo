<?php
session_start();

if (!isset($_SESSION["logado"])) {
    header("Location: login.php");
    exit;
}

$usuario_id = $_SESSION['usuario_id'];


require_once './classes/db_connect.php';

// Verifica se o ID do produto foi enviado via GET
if (isset($_GET['id'])) {
    $productId = $_GET['id'];

    // Obter os dados do produto do banco de dados
    $sql = "SELECT nome, descricao, foto FROM produtos WHERE id = :id AND usuario_id = :usuario_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $productId, ':usuario_id' => $usuario_id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($product) {
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
                <form action="editar_produto_backend.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?php echo $productId; ?>">
                    <label for="name">Nome:</label>
                    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($product['nome']); ?>"><br><br>
                    <label for="descricao">Descrição:</label>
                    <textarea id="descricao" name="descricao"><?php echo htmlspecialchars($product['descricao']); ?></textarea><br><br>
                    <label for="foto">Imagem:</label>
                    <input type="file" id="foto" name="foto"><br><br>
                    <img src="<?php echo htmlspecialchars($product['foto']); ?>" alt="Imagem atual" style="width: 150px; height: auto;"><br><br>
                    <button type="submit">Salvar</button>
                </form>
            </div>
            <?php require_once("./src/pages/footer/footer.html"); ?>
        </body>

        </html>

<?php
    } else {
        echo "Produto não encontrado.";
    }
} else {
    echo "ID do produto não especificado.";
}
?>