<?php
session_start();
if (!isset($_SESSION["logado"])) {
  header("Location: login.php");
}

$products = json_decode(file_get_contents('products.json'), true);
$usuario_especifico = $_GET['user'];

function displayProduct($products, $productId)
{
    foreach ($products as $product) {
        if ($product['id'] == $productId) {
            echo '<div class="card">';
            echo '<img src="' . $product['image'] . '" alt="">';
            echo '<h2>' . $product['name'] . '</h2>';
            echo '<h1>' . $product['usuario_dono'] . '</h1>';
            echo '<p>' . $product['description'] . '</p>';
            echo '</div>';
            break;
        }
    }
}

function displayInteressados($products, $productId)
{
    foreach ($products as $product) {
        if ($product['id'] == $productId && $product['interested']) {
            echo '<div class="title-ofertas"><h2>Interessados</h2></div>';
            foreach ($product['interessados'] as $interessado) {
                echo '<div class="interessado">';
                echo '<h2>Nome do Interessado: ' . $interessado['nome'] . '</h2>';
                echo '<h3>Produtos para Troca:</h3>';
                echo '<div class="dropdown">';
                echo '<button class="dropbtn">Selecione um Produto</button>';
                echo '<div class="dropdown-content">';
                foreach ($interessado['produtos'] as $produtoId) {
                    displayProduct($products, $produtoId);
                }
                echo '</div>';
                echo '</div>';
                echo '</div>';
            }
            break;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ofertas Recebidas - EcoEscambo</title>
  <link rel="stylesheet" href="./src//css//styleofertas.css">
  <link rel="shortcut icon" href="./src/img/header/logo-eco-escambo.jpg">
</head>

<body>
  <?php require_once("./src/pages/header/header.php"); ?>

  <div class="container-ofertasrecebidas">
    <div class="container">
      <div class="listProduct">
        <div class="titleproduto">
          <h2>Produto</h2>
        </div>
        <?php
                $productId = $_GET['id'];
                displayProduct($products, $productId);
                ?>
      </div>

      <div class="listInteressados">
        <?php
                displayInteressados($products, $productId);
                ?>
      </div>

    </div>
  </div>

  <?php require_once("./src/pages/footer/footer.html"); ?>
</body>

</html>