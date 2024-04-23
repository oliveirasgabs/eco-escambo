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
  <title>Detalhes - EcoEscambo</title>
  <link rel="stylesheet" href="/src/css/stylecatalogo.css">
  <link rel="shortcut icon" href="/src/img/header/logo-eco-escambo.jpg">
</head>

<body>
  <?php require_once("./src/pages/header/header.php") ?>
  <div class="container">
    <div class="title">Detalhes do Produto</div>
    <div class="detail">
      <div class="image">
        <img src="">
      </div>
      <div class="content">
        <h1 class="name"></h1>
        <div class="buttons">
          <button>Fazer oferta</button>
        </div>
        <div class="description"></div>
      </div>
    </div>

    <div class="title">Mais ofertas</div>
    <div class="listProduct"></div>
  </div>

  <script>
  let products = null;
  fetch('products.json') //obtem dados do arquivo.json
    .then(response => response.json())
    .then(data => {
      products = data;
      showDetail();
    })

  function showDetail() {
    let detail = document.querySelector('.detail');
    let listProduct = document.querySelector('.listProduct');
    let productId = new URLSearchParams(window.location.search).get('id');
    let thisProduct = products.filter(value => value.id == productId)[0];
    //se o product id não for econtrando retorna a pagina
    if (!thisProduct) {
      window.location.href = "/";
    }

    detail.querySelector('.image img').src = thisProduct.image;
    detail.querySelector('.name').innerText = thisProduct.name;
    detail.querySelector('.description').innerText = thisProduct.description;


    (products.filter(value => value.id != productId)).forEach(product => {
      let newProduct = document.createElement('a');
      newProduct.href = '/detail.php?id=' + product.id;
      newProduct.classList.add('item');
      newProduct.innerHTML =
        `<img src="${product.image}" alt="">
            <h2>${product.name}</h2>`;
      listProduct.appendChild(newProduct);
    });
  }
  </script>
  <?php require_once("./src/pages/footer/footer.html"); ?>
</body>

</html>