<?php
session_start();

$products = json_decode(file_get_contents('products.json'), true);

// Defina o nome do usuário específico
$usuario_especifico = "Fernando Haddad";

// Lógica para filtrar os produtos com interessados
function filterProductsByInterest($products)
{
    $interestedProducts = array_filter($products, function ($product) {
        return isset($product['interested']) && $product['interested'] === true;
    });
    return $interestedProducts;
}

// Lógica para pesquisar produtos por nome
function searchProductsByName($products, $name)
{
    $results = array();
    foreach ($products as $product) {
        $productName = strtolower($product['name']);
        $searchTerm = strtolower($name);
        // Verifica se o nome do produto contém o termo de pesquisa, em caso de falha na pesquisa exibir todos os produtos
        if (strpos($productName, $searchTerm) !== false) {
            $results[] = $product;
        }
    }
    return $results;
}

// Função para exibir os produtos
function displayProducts($products)
{
    global $usuario_especifico;
    foreach ($products as $product) {
        // Verifica se o produto pertence ao usuário específico
        if ($product['usuario_dono'] !== $usuario_especifico) {
            continue;
        }
        echo '<div class="card">';
        echo '<img src="' . $product['image'] . '" alt="">';
        echo '<h2>' . $product['name'] . '</h2>';
        echo '<div class="button-group">';
        // Botão "Editar" apenas se não houver interessados
        echo '<div class="btn-edit">';
        if (!$product['interested']) {
            echo '<div class="button-b1"><button type="button" onclick="window.location.href=\'editar_produto.php?id=' . $product['id'] . '\'">Editar</button></div>';
        }
        // Botão "Excluir"
        echo '<div class="button-b2"><button type="button" onclick="window.location.href=\'excluir_produto.php?id=' . $product['id'] . '\'">Excluir</button></div>';
        echo '</div>';
        // Botão "Ver Interessados" apenas se houver interessados
        if ($product['interested']) {
            echo '<div class="button-b3"><button type="button" onclick="window.location.href=\'ofertas_recebidas.php?id=' . $product['id'] . '&user=' . urlencode($usuario_especifico) . '\'">Ver Interessados</button></div>';
        }
        echo '</div>';
        echo '</div>';
    }
}


// Filtros, se possiveis
if (isset($_GET['filter'])) {
    $filter = $_GET['filter'];
    switch ($filter) {
        case 'interested':
            $products = filterProductsByInterest($products);
            break;
    }
} else {
    // Se nenhum filtro for selecionado, exibir todos os produtos
    $filter = 'all';
}

// Aplicar pesquisa por nome
if (isset($_GET['search'])) {
    $searchTerm = $_GET['search'];
    $products = searchProductsByName($products, $searchTerm);
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meus Produtos - EcoEscambo</title>
    <link rel="stylesheet" href="/src/css/meusprodutos.css">
    <link rel="shortcut icon" href="/src/img/header/logo-eco-escambo.jpg">
</head>

<body>
    <?php require_once("./src/pages/header/header.php"); ?>
    <div class="container--Prod">
        <div class="filtro">
            <div class="radio-group">
                <label class="radio-button">
                    <input type='radio' name='filter' value='all' <?php echo $filter === 'all' ? 'checked' : ''; ?> /> Todos | <span></span>
                </label>
                <label class="radio-button">
                    <input type='radio' name='filter' value='interested' <?php echo $filter === 'interested' ? 'checked' : ''; ?> /> Somente os que há interessados | <span></span>
                </label>
            </div>
            <form action="" method="GET">
                <input type="text" name="search" placeholder="Pesquisar por nome...">
                <button type="submit">Pesquisar</button>
            </form>
        </div>
        <div class="containe--Principal">
            <?php displayProducts($products); ?>
        </div>
    </div>
    <?php require_once("./src/pages/footer/footer.html"); ?>

    <script>
        const radioButtons = document.querySelectorAll('input[name="filter"]');
        radioButtons.forEach(radioButton => {
            radioButton.addEventListener('change', function() {
                // Obtém o valor do filtro selecionado
                const filter = this.value;
                // Atualiza o URL com o filtro selecionado
                const url = `?filter=${filter}`;
                // Redireciona para a nova URL
                window.location.href = url;
            });
        });
    </script>
</body>

</html>