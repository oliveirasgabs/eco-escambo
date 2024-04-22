<?php
if (isset($_GET['id'])) {
    $productId = $_GET['id'];

    $products = json_decode(file_get_contents('products.json'), true);

    $index = array_search($productId, array_column($products, 'id'));

    // Se o produto for encontrado vai excluilo-o
    if ($index !== false) {
        unset($products[$index]);

        // Salvar os produtos atualizados de volta no arquivo JSON
        file_put_contents('products.json', json_encode($products));

        // Redirecionar de volta para a página de produtos
        header("Location: meusprodutos.php");
        exit();
    } else {
        //redirecionar para a página de produtos ou exibir uma mensagem de erro caso em que o produto com o ID especificado não é encontrado
    }
} else {
    // caso em que nenhum ID de produto é fornecido redirecionar para a página de produtos ou exibir uma mensagem de erro
}
