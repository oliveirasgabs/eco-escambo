<?php
session_start();
if (!isset($_SESSION["logado"])) {
  header("Location: login.php");
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="shortcut icon" href="./src/img/header/logo-eco-escambo.jpg">
  <title>Cadastrar novo produto - EcoEscambo</title>
  <link rel="stylesheet" href="./src/css/style-signup-product.css">
</head>

<body>
  <?php require_once("./src/pages/header/header.php"); ?>
  <div class="container-sign-up-product">
    <div class="sign-up-title">
      <h1>Cadastre o seu produto</h1>
      <?php if (isset($_GET['erro'])): ?>
      <div class="error-message">
        <?php
        switch ($_GET['erro']) {
            case 1:
                echo "Todos os campos são obrigatórios.";
                break;
            case 2:
                echo "Formato de imagem inválido. Apenas JPG, JPEG, PNG e GIF são permitidos.";
                break;
            case 3:
                echo "Erro ao fazer upload da imagem.";
                break;
            case 4:
                echo "Erro ao encontrar o usuário. Faça login novamente.";
                break;
            default:
                echo "Erro desconhecido.";
        }
        ?>
      </div>
      <?php endif; ?>

    </div>
    <div class="container-product-info">
      <form action="valida-cadastro-produto.php" method="post" enctype="multipart/form-data">
        <label for="product-title">Título breve</label>
        <input type="text" name="product-title" id="" placeholder="Nome do produto">
        <label for="product-description">Descrição</label>
        <textarea type="text" name="product-description" id="product-description" rows="4"
          placeholder="Descrição do seu produto, detalhes e o que você achar importante informar"></textarea>
        <label for="">Foto do produto</label>
        <input type="file" id="product-image" name="product-image" accept="image/*">
        <button class="cadastrar-produto" type="submit">Enviar cadastro</button>
      </form>
    </div>
  </div>

  <?php require_once("./src/pages/footer/footer.html"); ?>
</body>

</html>