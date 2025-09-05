<?php
session_start();
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "FORNECEDOR") {
  header("Location: index.php");
  exit();
}

require_once "php/config.php";

// Buscar produtos do fornecedor
$stmt = $pdo->prepare("SELECT p.*, c.name AS category_name, u.name AS unit_name 
                       FROM products p
                       LEFT JOIN product_categories c ON p.category_id = c.id
                       LEFT JOIN units_of_measure u ON p.unit_id = u.id
                       WHERE p.supplier_id = :id
                       ORDER BY p.created_at DESC");
$stmt->execute([":id" => $_SESSION["user_id"]]);
$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Buscar categorias
$catStmt = $pdo->query("SELECT id, name FROM product_categories ORDER BY name ASC");
$categorias = $catStmt->fetchAll(PDO::FETCH_ASSOC);

// Buscar unidades
$unitStmt = $pdo->query("SELECT id, name FROM units_of_measure ORDER BY name ASC");
$units = $unitStmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard do Fornecedor - SupplyLink</title>
  <link rel="stylesheet" href="styles/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <style>
    header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 15px 40px;
      background: linear-gradient(90deg, #0077b6, #00b4d8);
      color: #fff;
    }
    .logo { display: flex; align-items: center; gap: 10px; }
    .user-menu { position: relative; }
    .user-btn {
      background: #fff; color: #0077b6;
      padding: 8px 15px; border-radius: 8px;
      border: none; cursor: pointer; font-weight: bold;
    }
    .dropdown {
      display: none; position: absolute; right: 0; top: 40px;
      background: #fff; border-radius: 8px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
      overflow: hidden;
    }
    .dropdown a {
      display: block; padding: 10px 15px;
      color: #333; text-decoration: none;
    }
    .dropdown a:hover { background: #f0f0f0; }
    .banner {
      text-align: center;
      background: linear-gradient(90deg, #0077b6, #00b4d8);
      color: #fff; padding: 50px 20px;
    }
    .banner h1 { font-size: 1.8rem; }
    .conteudo { padding: 30px; max-width: 1000px; margin: auto; }
    .btn-cadastrar {
      display: inline-block; padding: 12px 20px;
      background: #0077b6; color: #fff;
      border-radius: 8px; font-weight: bold;
      margin-bottom: 20px; border: none; cursor: pointer;
    }
    .produtos-grid {
      display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
      gap: 20px;
    }
    .produto-card {
      border: 1px solid #ddd; border-radius: 12px;
      overflow: hidden; box-shadow: 0 4px 10px rgba(0,0,0,0.1);
      background: #fff;
    }
    .produto-card img {
      width: 100%; height: 180px; object-fit: cover;
    }
    .produto-card .info { padding: 15px; }
    .produto-card h3 {
      margin: 0; font-size: 1.2rem; color: #0077b6;
    }
    .produto-card p { margin: 5px 0; font-size: 0.9rem; color: #555; }
    .sem-produto {
      text-align: center; padding: 40px;
      font-size: 1.1rem; color: #777;
    }
    /* Modal */
    .modal {
      display: none; position: fixed; z-index: 1000;
      left: 0; top: 0; width: 100%; height: 100%;
      background: rgba(0,0,0,0.6); justify-content: center; align-items: center;
    }
    .modal-content {
      background: #fff; padding: 25px; border-radius: 12px;
      width: 400px; max-height: 90%; overflow-y: auto;
      box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }
    .modal-content h2 { margin-bottom: 15px; color: #0077b6; }
    .modal-content label { margin-top: 10px; font-weight: bold; }
    .modal-content input, .modal-content select, .modal-content textarea {
      width: 100%; padding: 8px; margin-top: 5px;
      border-radius: 6px; border: 1px solid #ccc;
    }
    .close {
      float: right; font-size: 1.5rem; cursor: pointer; color: #333;
    }
  </style>
</head>
<body>
  <!-- Header -->
  <header>
    <div class="logo">
      <i class="fa-solid fa-truck-fast"></i>
      <h1>SupplyLink</h1>
    </div>
    <div class="user-menu">
      <button class="user-btn" onclick="toggleDropdown()">
        <?php echo htmlspecialchars($_SESSION["company_name"]); ?> <i class="fa-solid fa-caret-down"></i>
      </button>
      <div class="dropdown" id="dropdown-menu">
        <a href="php/logout.php">Sair</a>
      </div>
    </div>
  </header>

  <!-- Banner -->
  <section class="banner">
    <h1>A SupplyLink fica feliz por tê-lo como fornecedor!</h1>
    <p>Cadastre seus produtos e comece a ser encontrado por empresas de todo o Brasil.</p>
  </section>

  <!-- Conteúdo -->
  <section class="conteudo">
    <button class="btn-cadastrar" onclick="openModalProduto()">+ Cadastrar Produto</button>

    <?php if (count($produtos) > 0): ?>
      <div class="produtos-grid">
        <?php foreach ($produtos as $produto): ?>
          <div class="produto-card">
            <img src="uploads/<?php echo htmlspecialchars($produto['photo_url']); ?>" alt="<?php echo htmlspecialchars($produto['name']); ?>">
            <div class="info">
              <h3><?php echo htmlspecialchars($produto['name']); ?></h3>
              <p><strong>Categoria:</strong> <?php echo htmlspecialchars($produto['category_name']); ?></p>
              <p><strong>Preço:</strong> R$ <?php echo number_format($produto['price'], 2, ',', '.'); ?> / <?php echo htmlspecialchars($produto['unit_name']); ?></p>
              <p><strong>Estoque:</strong> <?php echo htmlspecialchars($produto['stock_quantity']); ?></p>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <div class="sem-produto">
        <p>Nenhum produto cadastrado ainda.<br> Cadastre um agora mesmo e comece a alcançar empresas!</p>
      </div>
    <?php endif; ?>
  </section>

  <!-- Modal Cadastro Produto -->
  <div id="modal-produto" class="modal">
    <div class="modal-content">
      <span class="close" onclick="closeModalProduto()">&times;</span>
      <h2>Cadastrar Produto</h2>

      <form action="php/register_product.php" method="POST" enctype="multipart/form-data">
        <label for="name">Nome do Produto</label>
        <input type="text" name="name" id="name" required>

        <label for="category_id">Categoria</label>
        <select name="category_id" id="category_id" required>
          <option value="">Selecione uma categoria</option>
          <?php foreach ($categorias as $cat): ?>
            <option value="<?php echo $cat['id']; ?>"><?php echo htmlspecialchars($cat['name']); ?></option>
          <?php endforeach; ?>
        </select>

        <label for="price">Preço (R$)</label>
        <input type="number" step="0.01" name="price" id="price" required>

        <label for="unit_id">Unidade de Medida</label>
        <select name="unit_id" id="unit_id" required>
          <option value="">Selecione uma unidade</option>
          <?php foreach ($units as $unit): ?>
            <option value="<?php echo $unit['id']; ?>"><?php echo htmlspecialchars($unit['name']); ?></option>
          <?php endforeach; ?>
        </select>

        <label for="stock">Quantidade em Estoque</label>
        <input type="number" name="stock" id="stock" required>

        <label for="description">Descrição</label>
        <textarea name="description" id="description" rows="3" required></textarea>

        <label for="image">Foto do Produto</label>
        <input type="url" name="image" id="image"  required>

        <button type="submit" class="btn-cadastrar">Salvar Produto</button>
      </form>
    </div>
  </div>

  <script>
    function toggleDropdown() {
      document.getElementById("dropdown-menu").style.display =
        document.getElementById("dropdown-menu").style.display === "block" ? "none" : "block";
    }
    window.onclick = function(event) {
      if (!event.target.matches('.user-btn') && !event.target.closest('.user-menu')) {
        document.getElementById("dropdown-menu").style.display = "none";
      }
    }
    function openModalProduto() {
      document.getElementById("modal-produto").style.display = "flex";
    }
    function closeModalProduto() {
      document.getElementById("modal-produto").style.display = "none";
    }
  </script>
</body>
</html>
