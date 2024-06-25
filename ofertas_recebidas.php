<?php
session_start();
require_once './classes/db_connect.php';

if (!isset($_SESSION["logado"])) {
  header("Location: login.php");
  exit;
}

function getProductById($pdo, $productId)
{
  $sql = "SELECT p.id, p.nome AS name, p.descricao AS description, p.foto AS image, u.nome AS usuario_dono
            FROM produtos p
            INNER JOIN usuarios u ON p.usuario_id = u.id
            WHERE p.id = ?";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([$productId]);
  return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getInteressadosByProduct($pdo, $productId)
{
  $sql = "SELECT i.id, i.interessado_id, i.produto_ofertado_id, i.status, 
                   u.nome AS nome_interessado, 
                   p.id AS produto_id, p.nome AS produto_nome, p.descricao AS produto_descricao, p.foto AS produto_imagem,
                   po.nome AS produto_ofertado_nome, po.descricao AS produto_ofertado_descricao, po.foto AS produto_ofertado_imagem
            FROM interesses i
            INNER JOIN produtos po ON i.produto_ofertado_id = po.id
            INNER JOIN usuarios u ON i.interessado_id = u.id
            INNER JOIN produtos p ON i.produto_id = p.id
            WHERE p.id = ?";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([$productId]);
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getUserProducts($pdo, $userId)
{
  $sql = "SELECT id, nome FROM produtos WHERE estado = 'disponível' AND usuario_id = ?";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([$userId]);
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

if (isset($_GET['id'])) {
  $productId = $_GET['id'];
  $product = getProductById($pdo, $productId);
  $interessados = getInteressadosByProduct($pdo, $productId);
} else {
  header("Location: meusprodutos.php");
  exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ofertas Recebidas - EcoEscambo</title>
  <link rel="stylesheet" href="./src/css/styleofertas.css">
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
        <div class="card">
          <img src="<?php echo $product['image']; ?>" alt="">
          <h2><?php echo $product['name']; ?></h2>
          <h1><?php echo $product['usuario_dono']; ?></h1>
          <p><?php echo $product['description']; ?></p>
        </div>
      </div>

      <div class="listInteressados">
        <div class="title-ofertas">
          <h2>Interessados</h2>
        </div>
        <?php if (!empty($interessados)) : ?>
          <?php foreach ($interessados as $interessado) : ?>
            <div class="card" id="interessado-<?php echo $interessado['id']; ?>">
              <h2>Nome do Interessado: <?php echo $interessado['nome_interessado']; ?></h2>
              <div class="produto-ofertado">
                <img src="<?php echo $interessado['produto_ofertado_imagem']; ?>" alt="">
                <h2><?php echo $interessado['produto_ofertado_nome']; ?></h2>
                <p><?php echo $interessado['produto_ofertado_descricao']; ?></p>
              </div>
              <div class="buttons">
                <?php if ($interessado['status'] === 'pendente') : ?>
                  <button type="button" onclick="aceitarOferta(<?php echo $interessado['id']; ?>)">Aceitar</button>
                  <button type="button" onclick="recusarOferta(<?php echo $interessado['id']; ?>)">Recusar</button>
                  <button type="button" onclick="toggleProposta(<?php echo $interessado['id']; ?>)">Propor Novo Item</button>
                  <div id="proposta-<?php echo $interessado['id']; ?>" style="display: none;">
                    <form id="form-proposta-<?php echo $interessado['id']; ?>" onsubmit="enviarNovaProposta(event, <?php echo $interessado['id']; ?>)">
                      <input type="hidden" name="interesse_id" value="<?php echo $interessado['id']; ?>">
                      <input type="hidden" name="produto_id" value="<?php echo $productId; ?>"> <!-- ID do produto original -->
                      <select name="novo_produto_id" required>
                        <option value="">Selecione um novo item...</option>
                        <?php
                        $userProducts = getUserProducts($pdo, $interessado['interessado_id']); // Produtos do interessado
                        foreach ($userProducts as $userProduct) : ?>
                          <option value="<?php echo $userProduct['id']; ?>"><?php echo $userProduct['nome']; ?></option>
                        <?php endforeach; ?>
                      </select>
                      <input type="text" name="nova_proposta" placeholder="Descrição da nova proposta" required>
                      <button type="submit">Enviar Nova Proposta</button>
                    </form>
                  </div>
                <?php elseif ($interessado['status'] === 'rejeitada') : ?>
                  <p>Status: Rejeitada</p>
                  <p>Oferta Recusada. Obrigado pelo interesse.</p>
                <?php else : ?>
                  <p>Status: Aceita</p>
                  <p>Negociação Concluída. Aguarde o contato por e-mail.</p>
                <?php endif; ?>
              </div>
            </div>
          <?php endforeach; ?>
        <?php else : ?>
          <p>Não há interessados para este produto no momento.</p>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <script>
    function toggleProposta(interesseId) {
      const propostaDiv = document.getElementById('proposta-' + interesseId);
      propostaDiv.style.display = propostaDiv.style.display === 'none' ? 'block' : 'none';
    }

    function aceitarOferta(interestId) {
      const formData = new FormData();
      formData.append('interesse_id', interestId);
      formData.append('produto_id', <?php echo $productId; ?>);
      formData.append('action', 'aceitar');

      fetch('processar_oferta.php', {
          method: 'POST',
          body: formData
        })
        .then(response => response.text())
        .then(data => {
          document.getElementById('interessado-' + interestId).innerHTML = `
                    <p>Status: Aceita</p>
                    <p>Negociação Concluída. Aguarde o contato por e-mail.</p>
                `;
        })
        .catch(error => {
          console.error('Erro ao processar aceitação:', error);
        });
    }

    function recusarOferta(interestId) {
      const formData = new FormData();
      formData.append('interesse_id', interestId);
      formData.append('produto_id', <?php echo $productId; ?>);
      formData.append('action', 'recusar');

      fetch('processar_oferta.php', {
          method: 'POST',
          body: formData
        })
        .then(response => response.text())
        .then(data => {
          document.getElementById('interessado-' + interestId).innerHTML = `
                    <p>Status: Rejeitada</p>
                    <p>Oferta Recusada. Obrigado pelo interesse.</p>
                `;
        })
        .catch(error => {
          console.error('Erro ao processar recusa:', error);
        });
    }

    function enviarNovaProposta(event, interesseId) {
      event.preventDefault();
      const form = document.getElementById('form-proposta-' + interesseId);
      const formData = new FormData(form);
      formData.append("action", "propor");

      fetch('processar_oferta.php', {
          method: 'POST',
          body: formData
        })
        .then(response => {
          if (!response.ok) {
            throw new Error('Erro ao processar a requisição HTTP: ' + response.status);
          }
          return response.json();
        })
        .then(data => {
          // Tratar a resposta do servidor
          console.log('Resposta do servidor:', data);
          location.reload();
        })
        .catch(error => {
          console.error('Erro ao enviar nova proposta:', error);

        });
    }
  </script>
</body>

</html>