<?php
session_start();
require_once "config.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "FORNECEDOR") {
  header("Location: ../index.php");
  exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  try {
    $name           = $_POST["name"] ?? null;
    $product_categories_id    = $_POST["product_categories_id"] ?? null;
    $price          = $_POST["price"] ?? null;
    $unit_id        = $_POST["unit_id"] ?? null;
    $stock_quantity = $_POST["stock"] ?? null;
    $description    = $_POST["description"] ?? null;

    if (!$name || !$product_categories_id || !$price || !$unit_id || !$stock_quantity || !$description) {
      throw new Exception("Preencha todos os campos obrigatórios.");
    }

    // Upload da imagem
    if (!empty($_FILES["image"]["name"])) {
      $targetDir = "../uploads/";
      if (!file_exists($targetDir)) {
        mkdir($targetDir, 0777, true);
      }
      $fileName = time() . "_" . basename($_FILES["image"]["name"]);
      $targetFile = $targetDir . $fileName;
      move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile);
    } else {
      $fileName = "default.png";
    }

    // Inserir produto
    $sql = "INSERT INTO products 
      (supplier_id, name, product_categories_id, price, unit_id, description, stock_quantity, photo_url, is_active, created_at, updated_at) 
      VALUES 
      (:supplier_id, :name, :product_categories_id, :price, :unit_id, :description, :stock_quantity, :photo_url, 1, NOW(), NOW())";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
      ":supplier_id"    => $_SESSION["user_id"],
      ":name"           => $name,
      ":product_categories_id"    => $product_categories_id,
      ":price"          => $price,
      ":unit_id"        => $unit_id,
      ":description"    => $description,
      ":stock_quantity" => $stock_quantity,
      ":photo_url"      => $fileName
    ]);

    header("Location: ../fornecedor_dashboard.php?success=1");
    exit();

  } catch (Exception $e) {
    echo "<p style='color:red; text-align:center;'>Erro: " . $e->getMessage() . "</p>";
    echo "<p style='text-align:center;'><a href='../fornecedor_dashboard.php'>Voltar</a></p>";
  }
} else {
  header("Location: ../fornecedor_dashboard.php");
  exit();
}
