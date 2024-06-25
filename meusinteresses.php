<?php
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION["logado"])) {
  header("Location: login.php");
  exit;
}

// Recupera o ID do usuário logado da sessão
$usuario_id = $_SESSION['usuario_id'];

// Conecta ao banco de dados utilizando PDO
require_once './classes/db_connect.php';

// Verifica se há uma pesquisa por nome
$search = isset($_GET['search']) ? $_GET['search'] : '';

try {
  // Ajusta a consulta SQL para exibir apenas os produtos em que o usuário aparece na coluna interessado_id
  $sql = "SELECT p.id, p.nome AS name, p.descricao, p.foto AS image, p.estado
          FROM produtos p
          JOIN interesses i ON p.id = i.produto_id
          WHERE i.interessado_id = :usuario_id";

  // Adiciona condição para pesquisa por nome
  if (!empty($search)) {
    $sql .= " AND LOWER(p.nome) LIKE :search";
    $searchTerm = "%" . strtolower($search) . "%";
  }

  // Ordena por estado decrescente
  $sql .= " ORDER BY p.estado DESC";

  // Prepara a consulta
  $stmt = $pdo->prepare($sql);

  // Bind dos parâmetros
  $stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
  if (!empty($search)) {
    $stmt->bindParam(':search', $searchTerm, PDO::PARAM_STR);
  }

  // Executa a consulta
  $stmt->execute();

  // Obtém o resultado da consulta
  $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
  // Tratamento de erro
  die("Erro ao executar consulta: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Meus Interesses - EcoEscambo</title>
  <link rel="stylesheet" href="./src/css/meusprodutos.css">
  <link rel="shortcut icon" href="./src/img/header/logo-eco-escambo.jpg">
</head>

<body>
  <?php require_once("./src/pages/header/header.php"); ?>
  <div class="container--Prod">
    <div class="filtro">
      <form action="" method="GET">
        <input type="text" name="search" placeholder="Pesquisar por nome..." value="<?php echo htmlspecialchars($search); ?>">
        <button type="submit">Pesquisar</button>
      </form>
    </div>
    <div class="containe--Principal">
      <!-- Os produtos serão renderizados dinamicamente pelo JavaScript -->
    </div>
    <div class="page"></div>
  </div>
  <?php require_once("./src/pages/footer/footer.html"); ?>

  <script>
    const products = <?php echo json_encode($products); ?>;
    let currentPage = 1;
    const itemsPerPage = 6;

    function displayProducts(productsToDisplay) {
      const container = document.querySelector('.containe--Principal');
      container.innerHTML = '';

      productsToDisplay.forEach(product => {
        const productDiv = document.createElement('div');
        productDiv.classList.add('card');

        let productHTML = `
                <img src="${product.image}" alt="">
                <h2>${product.name}</h2>
                <div class="button-group">
                  <div class="button-b3"><button type="button" onclick="window.location.href='./detail.php?id=${product.id}'">Ver Detalhes</button></div>
                </div>`;

        productDiv.innerHTML = productHTML;
        container.appendChild(productDiv);
      });
    }

    function addDataToHTML() {
      const startIndex = (currentPage - 1) * itemsPerPage;
      const endIndex = startIndex + itemsPerPage;
      const currentProducts = products.slice(startIndex, endIndex);

      displayProducts(currentProducts);
      renderPage(products);
    }

    function renderPage(filteredProducts) {
      const page = document.querySelector('.page');
      const totalPages = Math.ceil(filteredProducts.length / itemsPerPage);

      page.innerHTML = '';

      for (let i = 1; i <= totalPages; i++) {
        const pageButton = document.createElement('button');
        pageButton.textContent = i;
        pageButton.addEventListener('click', () => {
          currentPage = i;
          const startIndex = (currentPage - 1) * itemsPerPage;
          const endIndex = startIndex + itemsPerPage;
          const currentProducts = filteredProducts.slice(startIndex, endIndex);
          displayProducts(currentProducts);
        });
        if (i === currentPage) {
          pageButton.classList.add('active');
        }
        page.appendChild(pageButton);
      }
    }

    document.addEventListener('DOMContentLoaded', () => {
      addDataToHTML();
    });
  </script>

</body>

</html>
