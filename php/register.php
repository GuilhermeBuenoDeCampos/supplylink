<?php
session_start();
require_once "config.php"; // conexão com o banco via PDO

// Função para validar CPF
function validaCPF($cpf) {
    $cpf = preg_replace('/[^0-9]/', '', $cpf);
    if (strlen($cpf) != 11 || preg_match('/(\d)\1{10}/', $cpf)) return false;

    for ($t = 9; $t < 11; $t++) {
        for ($d = 0, $c = 0; $c < $t; $c++) {
            $d += $cpf[$c] * (($t + 1) - $c);
        }
        $d = ((10 * $d) % 11) % 10;
        if ($cpf[$c] != $d) return false;
    }
    return true;
}

// Função para validar CNPJ
function validaCNPJ($cnpj) {
    $cnpj = preg_replace('/[^0-9]/', '', $cnpj);
    if (strlen($cnpj) != 14) return false;

    $soma = 0;
    $peso = 5;
    for ($i = 0; $i < 12; $i++) {
        $soma += $cnpj[$i] * $peso;
        $peso--;
        if ($peso < 2) $peso = 9;
    }
    $digito1 = ($soma % 11 < 2) ? 0 : 11 - ($soma % 11);

    $soma = 0;
    $peso = 6;
    for ($i = 0; $i < 13; $i++) {
        $soma += $cnpj[$i] * $peso;
        $peso--;
        if ($peso < 2) $peso = 9;
    }
    $digito2 = ($soma % 11 < 2) ? 0 : 11 - ($soma % 11);

    return ($cnpj[12] == $digito1 && $cnpj[13] == $digito2);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    try {
        // Dados recebidos
        $role            = $_POST["role"] ?? null;
        $email           = $_POST["email"] ?? null;
        $phone           = $_POST["phone"] ?? null;
        $password        = $_POST["password"] ?? null;
        $confirmPassword = $_POST["confirm_password"] ?? null;
        $cpf_cnpj        = $_POST["cpf_cnpj"] ?? null;
        $responsible     = $_POST["responsible_name"] ?? null;
        $company         = $_POST["company_name"] ?? null;
        $cep             = $_POST["cep"] ?? null;
        $street          = $_POST["street"] ?? null;
        $number          = $_POST["number"] ?? null;
        $complement      = $_POST["complement"] ?? null;
        $city            = $_POST["city"] ?? null;
        $state           = $_POST["state"] ?? null;

        // Verificação de campos obrigatórios
        if (!$role || !$email || !$phone || !$cpf_cnpj || !$responsible || !$company || !$cep || !$street || !$number || !$city || !$state || !$password || !$confirmPassword) {
            throw new Exception("Todos os campos obrigatórios devem ser preenchidos.");
        }

        // Confirmação de senha
        if ($password !== $confirmPassword) {
            throw new Exception("As senhas não coincidem.");
        }

        // Validação CPF/CNPJ
        $cpf_cnpj_digits = preg_replace('/[^0-9]/', '', $cpf_cnpj);
        if (strlen($cpf_cnpj_digits) <= 11) {
            if (!validaCPF($cpf_cnpj_digits)) {
                throw new Exception("CPF inválido.");
            }
        } else {
            if (!validaCNPJ($cpf_cnpj_digits)) {
                throw new Exception("CNPJ inválido.");
            }
        }

        // Verifica se o e-mail já existe
        $check = $pdo->prepare("SELECT id FROM accounts WHERE email = :email");
        $check->execute([":email" => $email]);
        if ($check->rowCount() > 0) {
            throw new Exception("Já existe uma conta cadastrada com este e-mail.");
        }

        // Hash seguro da senha
        $passwordHash = password_hash($password, PASSWORD_BCRYPT);

        // Inserção
        $sql = "INSERT INTO accounts 
        (role, email, phone, password_hash, cpf_cnpj, responsible_name, company_name, cep, street, number, complement, city, state) 
        VALUES 
        (:role, :email, :phone, :password, :cpf_cnpj, :responsible, :company, :cep, :street, :number, :complement, :city, :state)";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ":role"        => $role,
            ":email"       => $email,
            ":phone"       => $phone,
            ":password"    => $passwordHash,
            ":cpf_cnpj"    => $cpf_cnpj_digits,
            ":responsible" => $responsible,
            ":company"     => $company,
            ":cep"         => $cep,
            ":street"      => $street,
            ":number"      => $number,
            ":complement"  => $complement,
            ":city"        => $city,
            ":state"       => $state
        ]);

        // Recupera ID do usuário
        $userId = $pdo->lastInsertId();

        // Inicia sessão
        $_SESSION["user_id"]      = $userId;
        $_SESSION["role"]         = $role;
        $_SESSION["company_name"] = $company;

        // Redireciona para o dashboard
        header("Location: ../dashboard.php?success=1");
        exit();

    } catch (Exception $e) {
        echo "<p style='color:red; text-align:center; font-family:sans-serif;'>Erro: " . $e->getMessage() . "</p>";
        echo "<p style='text-align:center;'><a href='../index.php'>Voltar</a></p>";
    }
} else {
    header("Location: ../index.php");
    exit();
}
