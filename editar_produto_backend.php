<?php
// Verifica se os dados do formulário foram enviados via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtém os dados do formulário
    $productId = $_POST['id'];
    $productName = $_POST['name'];
    $productOwner = $_POST['dono'];
    // Adicione aqui os outros campos que deseja editar

    // Carrega os produtos do arquivo JSON
    $products = json_decode(file_get_contents('products.json'), true);

    // Procura o índice do produto com o ID especificado
    $index = array_search($productId, array_column($products, 'id'));

    // Se o produto for encontrado, atualiza os dados
    if ($index !== false) {
        $products[$index]['name'] = $productName;
        $products[$index]['dono'] = $productOwner;
        // Adicione aqui as atualizações para outros campos, se necessário

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
