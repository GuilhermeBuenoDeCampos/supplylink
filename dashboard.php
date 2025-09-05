<?php
session_start();

// Se não estiver logado, volta para o index
if (!isset($_SESSION["user_id"])) {
    header("Location: index.php");
    exit();
}

$userRole = $_SESSION["role"];
$userId = $_SESSION["user_id"];
$userName = $_SESSION["company_name"] ?? "Usuário";
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Dashboard - SupplyLink</title>
  <link rel="stylesheet" href="styles/style.css">
</head>
<body>
  <header class="gradiant-bg">
    <div class="container-header">
      <div class="logo">
        <i class="fa-solid fa-truck-fast text-white caminhao"></i>
        <h1 class="text-white">SupplyLink</h1>
      </div>
      <nav>
        <a href="index.php" class="text-white">Início</a>
        <a href="php/logout.php" class="text-white">Sair</a>
      </nav>
    </div>
  </header>

  <main style="padding:40px; text-align:center;">
    <h1>Bem-vindo(a), <?php echo htmlspecialchars($userName); ?> 🎉</h1>
    <p>Você está logado como <strong><?php echo $userRole; ?></strong>.</p>

    <?php if ($userRole === "FORNECEDOR"): ?>
      <h2>Área do Fornecedor</h2>
      <p>Aqui você pode cadastrar e gerenciar seus produtos.</p>
      <a href="php/add_product.php" class="btn btn-cadastro">Cadastrar Produto</a>
    <?php elseif ($userRole === "EMPRESA"): ?>
      <h2>Área da Empresa</h2>
      <p>Aqui você pode procurar fornecedores e adicionar produtos ao seu carrinho.</p>
      <a href="php/add_to_cart.php" class="btn btn-login">Adicionar ao Carrinho</a>
    <?php endif; ?>
  </main>

  <footer class="footer">
    <p class="text-light-gray">&copy; 2025 SupplyLink. Todos os direitos reservados.</p>
  </footer>
</body>
</html>
