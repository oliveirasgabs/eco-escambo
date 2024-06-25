<?php
session_start();

require_once './classes/db_connect.php';

if (!isset($_SESSION["logado"])) {
  header("Location: login.php");
  exit;
}

// Verifica se foi enviado o formulário de cancelar oferta
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancelar_oferta'])) {
  $produto_id = $_GET['id']; // ID do produto atual
  $usuario_id = $_SESSION['usuario_id'];

  // Remove a oferta existente
  $sql_remover_oferta = "DELETE FROM interesses WHERE produto_id = ? AND interessado_id = ?";
  $stmt_remover_oferta = $pdo->prepare($sql_remover_oferta);
  $stmt_remover_oferta->execute([$produto_id, $usuario_id]);

  // Redireciona de volta para a página de detalhes com uma mensagem de sucesso
  header("Location: detail.php?id=$produto_id&oferta_cancelada=true");
  exit;
}

// Verifica se foi enviado o formulário de fazer oferta
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['oferecer']) && isset($_POST['produto_troca'])) {
  $produto_id = $_GET['id']; // ID do produto atual
  $produto_ofertado_id = $_POST['produto_troca']; // ID do produto que o usuário está oferecendo em troca
  $usuario_id = $_SESSION['usuario_id'];

  // Verifica se o usuário já fez uma oferta para este produto
  $sql_check_oferta = "SELECT COUNT(*) FROM interesses WHERE produto_id = ? AND interessado_id = ?";
  $stmt_check_oferta = $pdo->prepare($sql_check_oferta);
  $stmt_check_oferta->execute([$produto_id, $usuario_id]);
  $oferta_existente = $stmt_check_oferta->fetchColumn();

  if ($oferta_existente > 0) {
    // Redireciona de volta para a página de detalhes com uma mensagem de erro
    header("Location: detail.php?id=$produto_id&oferta_existente=true");
    exit;
  }

  // Verifica se está respondendo a uma oferta existente
  $proposta_original_id = isset($_POST['proposta_original_id']) ? $_POST['proposta_original_id'] : null;

  // Insere a oferta na tabela de interesses
  $proposta = "Oferta feita para o produto $produto_id em troca do produto $produto_ofertado_id.";
  $status = 'pendente';
  $sql = "INSERT INTO interesses (produto_id, interessado_id, produto_ofertado_id, proposta, status, proposta_original_id)
            VALUES (?, ?, ?, ?, ?, ?)";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([$produto_id, $usuario_id, $produto_ofertado_id, $proposta, $status, $proposta_original_id]);

  // Redireciona de volta para a página de detalhes com uma mensagem de sucesso
  header("Location: detail.php?id=$produto_id&oferta_enviada=true");
  exit;
}

// Obtém o ID do produto da URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
  die("ID do produto inválido.");
}
$produto_id = $_GET['id'];

// Consulta para obter detalhes do produto do usuario atual
$sql_produto = "SELECT p.id, p.nome AS name, p.descricao, p.foto AS image, p.usuario_id, u.nome AS usuario_dono
                FROM produtos p
                INNER JOIN usuarios u ON p.usuario_id = u.id
                WHERE p.id = ?";
$stmt_produto = $pdo->prepare($sql_produto);
$stmt_produto->execute([$produto_id]);
$produto = $stmt_produto->fetch(PDO::FETCH_ASSOC);

if (!$produto) {
  die("Produto não encontrado.");
}

$usuario_id = $_SESSION['usuario_id'];

$sql_check_oferta = "SELECT COUNT(*) FROM interesses WHERE produto_id = ? AND interessado_id = ?";
$stmt_check_oferta = $pdo->prepare($sql_check_oferta);
$stmt_check_oferta->execute([$produto_id, $usuario_id]);
$oferta_existente = $stmt_check_oferta->fetchColumn();

// Consulta para obter os produtos disponíveis para troca do usuário logado
$sql_produtos_troca = "SELECT id, nome FROM produtos WHERE usuario_id = ? AND estado = 'disponível'";
$stmt_produtos_troca = $pdo->prepare($sql_produtos_troca);
$stmt_produtos_troca->execute([$usuario_id]);
$produtos_troca = $stmt_produtos_troca->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Detalhes do Produto - EcoEscambo</title>
  <link rel="stylesheet" href="./src/css/stylecatalogo.css">
  <link rel="shortcut icon" href="./src/img/header/logo-eco-escambo.jpg">
</head>

<body>
  <?php require_once("./src/pages/header/header.php") ?>
  <div class="container">
    <div class="title">Detalhes do Produto</div>
    <div class="detail">
      <div class="image">
        <img src="<?php echo $produto['image']; ?>" alt="">
      </div>
      <div class="content">
        <h1 class="name"><?php echo $produto['name']; ?></h1>
        <div class="buttons">
          <?php if ($oferta_existente > 0) : ?>
            <p>Você já fez uma oferta para este produto.</p>
            <form action="" method="POST">
              <button type="submit" name="cancelar_oferta">Cancelar Oferta</button>
            </form>
          <?php else : ?>
            <form action="" method="POST">
              <label for="produto_troca">Escolha um produto para oferecer em troca:</label>
              <select name="produto_troca" id="produto_troca" required>
                <option value="">Selecione...</option>
                <?php foreach ($produtos_troca as $produto_troca) : ?>
                  <option value="<?php echo $produto_troca['id']; ?>"><?php echo $produto_troca['nome']; ?></option>
                <?php endforeach; ?>
              </select>

              <!-- Campo oculto para enviar o proposta_original_id -->
              <input type="hidden" name="proposta_original_id" value="<?php echo isset($_GET['proposta_original_id']) ? $_GET['proposta_original_id'] : ''; ?>">

              <button type="submit" name="oferecer">Fazer Oferta</button>
            </form>
          <?php endif; ?>
        </div>
        <div class="description">
          <h2>Descrição</h2>
          <p><?php echo $produto['descricao']; ?></p>
        </div>
      </div>
    </div>

    <?php if (isset($_GET['oferta_enviada']) && $_GET['oferta_enviada'] === 'true') : ?>
      <p>Seu interesse foi registrado com sucesso. Aguarde o contato do ofertante!</p>
    <?php elseif (isset($_GET['oferta_existente']) && $_GET['oferta_existente'] === 'true') : ?>
      <p>Você já fez uma oferta para este produto.</p>
    <?php elseif (isset($_GET['oferta_cancelada']) && $_GET['oferta_cancelada'] === 'true') : ?>
      <p>Oferta cancelada com sucesso!</p>
    <?php endif; ?>

    <div class="title"></div>
    <div class="listProduct">
      <!-- Exibir mais produtos aqui, se desejar -->
    </div>
  </div>

  <?php require_once("./src/pages/footer/footer.html"); ?>
</body>

</html>