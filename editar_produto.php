<?php
// Verifica se o ID do produto foi enviado via GET
if (isset($_GET['id'])) {
    $productId = $_GET['id'];

    // Carrega os produtos do arquivo JSON
    $products = json_decode(file_get_contents('products.json'), true);

    // Procura o índice do produto com o ID especificado
    $index = array_search($productId, array_column($products, 'id'));

    // Se o produto for encontrado, exibe o formulário de edição
    if ($index !== false) {
        $product = $products[$index];
?>

        <!DOCTYPE html>
        <html lang="pt-br">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Editar Produto</title>
        </head>

        <body>
            <h1>Editar Produto</h1>
            <form action="editar_produto_backend.php" method="POST">
                <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
                <label for="name">Nome:</label>
                <input type="text" id="name" name="name" value="<?php echo $product['name']; ?>"><br><br>
                <label for="dono">Dono:</label>
                <input type="text" id="dono" name="dono" value="<?php echo $product['dono']; ?>"><br><br>
                <!-- Adicione os outros campos que deseja editar -->
                <button type="submit">Salvar</button>
            </form>
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