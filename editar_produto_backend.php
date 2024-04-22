<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productId = $_POST['id'];
    $productName = $_POST['name'];
    $productOwner = $_POST['usuario_dono'];

    // Carrega os produtos do arquivo JSON
    $products = json_decode(file_get_contents('products.json'), true);

    // Procura o índice do produto com o ID especificado
    $index = array_search($productId, array_column($products, 'id'));

    // Se o produto for encontrado, atualiza os dados
    if ($index !== false) {
        $products[$index]['name'] = $productName;
        $products[$index]['usuario_dono'] = $productOwner;

        // Salva os produtos atualizados de volta no arquivo JSON
        file_put_contents('products.json', json_encode($products));

        // Redireciona de volta para a página de produtos
        header("Location: meusprodutos.php");
        exit();
    } else {
        echo "Produto não encontrado.";
    }
} else {
    echo "Método de requisição inválido.";
}
