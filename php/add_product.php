<?php
require "config.php";
session_start();

// Apenas fornecedores podem cadastrar
if ($_SESSION["role"] !== "FORNECEDOR") {
    die("❌ Acesso negado!");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $supplier_id = $_SESSION["user_id"];
    $name = $_POST["name"];
    $category_id = $_POST["category_id"];
    $price = $_POST["price"];
    $unit_id = $_POST["unit_id"];
    $description = $_POST["description"];
    $stock = $_POST["stock_quantity"];
    $photo_url = $_POST["photo_url"] ?? null;

    $sql = "INSERT INTO products (supplier_id, name, category_id, price, unit_id, description, stock_quantity, photo_url) 
            VALUES (:supplier, :name, :category, :price, :unit, :description, :stock, :photo)";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ":supplier" => $supplier_id,
        ":name" => $name,
        ":category" => $category_id,
        ":price" => $price,
        ":unit" => $unit_id,
        ":description" => $description,
        ":stock" => $stock,
        ":photo" => $photo_url
    ]);

    echo "✅ Produto cadastrado com sucesso!";
}
?>
