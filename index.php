<?php
session_start();
require_once './classes/db_connect.php'; // Inclua o arquivo de conexão com o banco de dados

// Verifica se o usuário está logado
if (!isset($_SESSION["logado"])) {
  header("Location: login.php");
  exit;
}

// Obtenha o ID do usuário logado
$userId = $_SESSION["usuario_id"];

// Função para buscar produtos do banco de dados, excluindo os produtos do usuário atual
function getSuggestedProducts($pdo, $userId)
{
  $sql = "SELECT id, nome, descricao, foto FROM produtos WHERE estado = 'disponível' AND usuario_id != ?";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([$userId]);
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$produtos = getSuggestedProducts($pdo, $userId);
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Eco Escambo</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@48,400,0,0" />
  <link rel="shortcut icon" href="./src/img/header/logo-eco-escambo.jpg">
  <link rel="stylesheet" href="./src/css/style.css">
  <script src="./src/js/carousel.js" defer></script>
  <script src="./src/js/carouselbanner.js" defer></script>
</head>

<body>
  <?php require_once("./src/pages/header/header.php"); ?>
  <div class="container">
    <div class="banner">
      <div class="slidebanner active">
        <img src="./src/img/banner0.png">
      </div>
      <div class="slidebanner">
        <img src="./src/img/banner1.png">
      </div>
      <div class="slidebanner">
        <img src="./src/img/banner2.png">
      </div>
      <div class="navigation">
        <div class="btn active"></div>
        <div class="btn"></div>
        <div class="btn"></div>
      </div>
    </div>

    <div class="slider-titulo">
      <h2>Sugestões que podem te interessar</h2>
    </div>
    <div class="container-body">
      <div class="slider-container">
        <div class="slider-box">
          <button id="prev-slide" class="slide-button material-symbols-rounded">
            chevron_left
          </button>
          <ul class="image-list">
            <?php foreach ($produtos as $produto) : ?>
              <li>
                <a href="detail.php?id=<?php echo $produto['id']; ?>">
                  <img src="<?php echo $produto['foto']; ?>" alt="<?php echo htmlspecialchars($produto['nome']); ?>" class="image-item">
                </a>
              </li>
            <?php endforeach; ?>
          </ul>
          <button id="next-slide" class="slide-button material-symbols-rounded">
            chevron_right
          </button>
        </div>
        <div class="slider-scrollbar">
          <div class="scrollbar-track">
            <div class="scrollbar-thumb"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php require_once("./src/pages/footer/footer.html"); ?>
</body>

</html>