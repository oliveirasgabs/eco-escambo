<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catalogo - EcoEscambo</title>
    <link rel="stylesheet" href="/src//css//stylecatalogo.css">
    <link rel="shortcut icon" href="/src/img/header/logo-eco-escambo.jpg">
</head>

<body>
    <?php require_once("./src/pages/header/header.php") ?>
    <div class="containerMax">
        <div class="container">
            <div class="title">Produtos</div>
            <div class="dono"></div>
            <div class="listProduct">
            </div>
            <div class="page"></div>
        </div>

        <script>
            let products = null;
            let currentPage = 1;
            let itemsPerPage = 8; // Defina o número de itens por página

            fetch('products.json')
                .then(response => response.json())
                .then(data => {
                    products = data;
                    addDataToHTML();
                });

            function addDataToHTML() {
                const listProductHTML = document.querySelector('.listProduct');
                const page = document.querySelector('.page');

                const startIndex = (currentPage - 1) * itemsPerPage;
                const endIndex = startIndex + itemsPerPage;
                const currentProducts = products.slice(startIndex, endIndex);

                listProductHTML.innerHTML = '';

                currentProducts.forEach(product => {
                    const newProduct = document.createElement('a');
                    newProduct.href = '/detail.php?id=' + product.id;
                    newProduct.classList.add('item');
                    newProduct.innerHTML =
                        `<img src="${product.image}" alt="">
                        <h2>${product.name}</h2>
                        <h1>${product.dono}</h1>
                        <div class="button"><button type="button">Tenho Interesse</button></div>`;
                    listProductHTML.appendChild(newProduct);
                });

                renderpage();
            }

            function renderpage() {
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
        </script>
    </div>
    <?php require_once("./src/pages/footer/footer.html"); ?>
</body>

</html>