<?php
$menuOnline = "<nav class='menu'>
  <ul>
    <li><a href='./catalogo.php'>Catálogo</a></li>
    <li><a href='./meusinteresses.php'>Meus interesses</a></li>
    <li><a href='./meusprodutos.php'>Meus produtos</a></li>
    <li><a href='./cadastrar-produto.php'><button>Cadastrar produto</button></a></li>
    <li><a href='./logout.php'>Sair</a></li>
  </ul>
</nav>";

$menuOffline = "<nav class='menu'>
  <ul>
    <li><a href='./cadastro.php'>Crie sua conta</a></li>
    <li><a href='./login.php'><button>Entrar</button></a></li>
  </ul>
</nav>";

if (isset($_SESSION["logado"]) == true) {
  $menu = $menuOnline;
} else {
  $menu = $menuOffline;
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Header - EcoEscambo</title>
  <link rel="stylesheet" href="./src/css/styleheader.css">
  <link rel="shortcut icon" type="imagex/png" href="./src/img/header/logo-eco-escambo.jpg">
</head>

<body>
  <div id="header">
    <div class="menu--content">
      <div><a href="index.php"><img src="./src/img/header/logo-eco-escambo.jpg" alt="eco-escambo" id="company-logo"></a>
      </div>
      <div id="company-name"><a href="index.php">Eco Escambo</a></div>
      <?php echo $menu
      ?>
    </div>
  </div>
</body>

</html>