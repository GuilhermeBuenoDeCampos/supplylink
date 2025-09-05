<?php
session_start();
require_once "config.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST["email"] ?? null;
    $password = $_POST["password"] ?? null;

    if (!$email || !$password) {
        die("Preencha e-mail e senha.");
    }

    $stmt = $pdo->prepare("SELECT * FROM accounts WHERE email = :email LIMIT 1");
    $stmt->execute([":email" => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user["password_hash"])) {
        // Cria sessão
        $_SESSION["user_id"]      = $user["id"];
        $_SESSION["role"]         = $user["role"];
        $_SESSION["company_name"] = $user["company_name"];

        // Redireciona de acordo com o tipo
        if ($user["role"] === "FORNECEDOR") {
            header("Location: ../fornecedor_dashboard.php");
        } else {
            header("Location: ../empresa_dashboard.php");
        }
        exit();
    } else {
        echo "<p style='color:red; text-align:center;'>E-mail ou senha inválidos.</p>";
        echo "<p style='text-align:center;'><a href='../index.php'>Voltar</a></p>";
    }
} else {
    header("Location: ../index.php");
    exit();
}
