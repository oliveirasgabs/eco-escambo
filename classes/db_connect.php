<?php
  // Configurações de conexão com o banco de dados
  $host = 'localhost';
  $dbname = 'ecoescambo';
  $username = 'root';
  $password = '';

  try {
      // Cria uma nova instância do PDO
      $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
      
      // Define o modo de erro do PDO para exceções
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      echo "Conexão efetuada!";
  } catch (PDOException $e) {
      // Tratamento de erro
      die("Erro ao conectar com o banco de dados: " . $e->getMessage());
  }
?>