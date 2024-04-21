<?php
if (isset($_GET['id'])) {
    $productId = $_GET['id'];

    // Carregar os produtos do arquivo JSON
    $products = json_decode(file_get_contents('products.json'), true);

    // Procurar o índice do produto com o ID especificado
    $index = array_search($productId, array_column($products, 'id'));

    // Se o produto for encontrado, exclua-o
    if ($index !== false) {
        unset($products[$index]);

        // Salvar os produtos atualizados de volta no arquivo JSON
        file_put_contents('products.json', json_encode($products));

        // Redirecionar de volta para a página de produtos
        header("Location: meusprodutos.php");
        exit();
    } else {
        // Lidar com o caso em que o produto com o ID especificado não é encontrado
        // Por exemplo, redirecionar para a página de produtos ou exibir uma mensagem de erro
    }
} else {
    // Lidar com o caso em que nenhum ID de produto é fornecido
    // Por exemplo, redirecionar para a página de produtos ou exibir uma mensagem de erro
}
