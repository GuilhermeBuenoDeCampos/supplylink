<?php
require "config.php";
session_start();

// Apenas empresas podem adicionar ao carrinho
if ($_SESSION["role"] !== "EMPRESA") {
    die("❌ Apenas empresas podem usar o carrinho.");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $company_id = $_SESSION["user_id"];
    $product_id = $_POST["product_id"];
    $quantity = $_POST["quantity"];

    // Criar carrinho se não existir
    $stmt = $pdo->prepare("SELECT id FROM carts WHERE company_id = :cid AND status = 'OPEN'");
    $stmt->execute([":cid" => $company_id]);
    $cart = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$cart) {
        $pdo->prepare("INSERT INTO carts (company_id) VALUES (:cid)")
            ->execute([":cid" => $company_id]);
        $cart_id = $pdo->lastInsertId();
    } else {
        $cart_id = $cart["id"];
    }

    // Pegar preço atual do produto
    $stmt = $pdo->prepare("SELECT price FROM products WHERE id = :pid");
    $stmt->execute([":pid" => $product_id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$product) {
        die("❌ Produto não encontrado.");
    }

    $price = $product["price"];

    // Inserir no carrinho (ou atualizar se já existir)
    $sql = "INSERT INTO cart_items (cart_id, product_id, quantity, unit_price)
            VALUES (:cart, :product, :quantity, :price)
            ON DUPLICATE KEY UPDATE quantity = quantity + VALUES(quantity)";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ":cart" => $cart_id,
        ":product" => $product_id,
        ":quantity" => $quantity,
        ":price" => $price
    ]);

    echo "✅ Produto adicionado ao carrinho!";
}
?>
