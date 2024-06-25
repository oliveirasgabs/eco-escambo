<?php
session_start();
require_once './classes/db_connect.php';

if (!isset($_SESSION["logado"])) {
  header("Location: login.php");
  exit();
}

try {
  // Consulta SQL com JOIN para selecionar produtos com nome de usuário
  $sql = "SELECT p.id, p.nome, p.descricao, p.foto, p.estado, p.data_criacao, p.data_atualizacao, p.em_troca, u.nome as usuario_nome
            FROM produtos p
            INNER JOIN usuarios u ON p.usuario_id = u.id
            WHERE p.usuario_id != :usuario_id AND p.estado = 'disponível'";
  $stmt = $pdo->prepare($sql);
  $stmt->bindParam(':usuario_id', $_SESSION['usuario_id'], PDO::PARAM_INT);
  $stmt->execute();
  $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  die("Erro ao buscar produtos: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Catalogo - EcoEscambo</title>
  <link rel="stylesheet" href="./src/css/stylecatalogo.css">
  <link rel="shortcut icon" href="./src/img/header/logo-eco-escambo.jpg">
</head>

<body>
  <?php require_once("./src/pages/header/header.php"); ?>

  <div class="containerMax">
    <div class="container">
      <div class="title">Produtos</div>
      <div class="listProduct">
        <!-- Renderizar os produtos dinamicamente -->
        <?php foreach ($products as $product) : ?>
          <a href="./detail.php?id=<?php echo $product['id']; ?>" class="item">
            <img src="<?php echo $product['foto']; ?>" alt="">
            <h2><?php echo $product['nome']; ?></h2>
            <h1><?php echo $product['usuario_nome']; ?></h1>
            <!-- Adicione outros detalhes do produto conforme necessário -->
            <div class="button"><button type="button">Tenho Interesse</button></div>
          </a>
        <?php endforeach; ?>
      </div>
      <div class="page"></div>
    </div>

    <script>
      let products = <?php echo json_encode($products); ?>;
      let currentPage = 1;
      let itemsPerPage = 8;

      function addDataToHTML() {
        const listProductHTML = document.querySelector('.listProduct');
        const page = document.querySelector('.page');

        const startIndex = (currentPage - 1) * itemsPerPage;
        const endIndex = startIndex + itemsPerPage;
        const currentProducts = products.slice(startIndex, endIndex);

        listProductHTML.innerHTML = '';

        currentProducts.forEach(product => {
          const newProduct = document.createElement('a');
          newProduct.href = './detail.php?id=' + product.id;
          newProduct.classList.add('item');
          newProduct.innerHTML =
            `<img src="${product.foto}" alt="">
                        <h2>${product.nome}</h2>
                        <h1>${product.usuario_nome}</h1>
                        <div class="button"><button type="button">Tenho Interesse</button></div>`;
          listProductHTML.appendChild(newProduct);
        });

        renderPage();
      }

      function renderPage() {
        const page = document.querySelector('.page');
        const totalPages = Math.ceil(products.length / itemsPerPage);

        page.innerHTML = '';

        for (let i = 1; i <= totalPages; i++) {
          const pageButton = document.createElement('button');
          pageButton.textContent = i;
          pageButton.addEventListener('click', () => {
            currentPage = i;
            addDataToHTML();
          });
          page.appendChild(pageButton);
        }
      }

      document.addEventListener('DOMContentLoaded', () => {
        addDataToHTML();
      });
    </script>
  </div>

  <?php require_once("./src/pages/footer/footer.html"); ?>
</body>

</html>