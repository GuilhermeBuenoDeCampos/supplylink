<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SupplyLink - Conectando empresas a fornecedores</title>
  <link rel="stylesheet" href="styles/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <style>
    /* --- Estilos extras para modal multi-step --- */
    .modal-steps {
      max-width: 500px;
      margin: auto;
      padding: 30px;
      border-radius: 15px;
      background: #fff;
      box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }
    .form-step {
      display: none;
    }
    .form-step.active {
      display: block;
      animation: fadeIn 0.5s;
    }
    .step-buttons {
      display: flex;
      justify-content: space-between;
      margin-top: 15px;
    }
    @keyframes fadeIn {
      from {opacity: 0; transform: translateY(10px);}
      to {opacity: 1; transform: translateY(0);}
    }
    label {
      display: block;
      margin-top: 10px;
      font-weight: bold;
      font-size: 0.9rem;
      color: #333;
    }
    input, select {
      width: 100%;
      padding: 8px;
      margin-top: 5px;
      border-radius: 8px;
      border: 1px solid #ccc;
    }
    .error {
      color: red;
      font-size: 0.8rem;
      display: none;
      margin-top: 3px;
    }
    input.invalid {
      border: 1px solid red;
    }
  </style>
</head>
<body>
  <!-- Header -->
  <header class="gradiant-bg">
    <div class="container-header">
      <div class="logo">
        <i class="fa-solid fa-truck-fast text-white caminhao"></i>
        <h1 class="text-white">SupplyLink</h1>
      </div>
      <nav>
        <a href="#" class="text-white">Início</a>
        <a href="#" class="text-white">Para Fornecedores</a>
        <a href="#" class="text-white">Contato</a>
      </nav>
      <div class="btns">
        <button class="btn btn-cadastro" onclick="openModal('cadastro')">Cadastre-se</button>
        <button class="btn btn-login" onclick="openModal('login')">Login</button>
      </div>
    </div>
  </header>

  <!-- Banner -->
  <section class="gradiant-bg text-white">
    <div class="container-banner">
      <div class="banner-text">
        <h1><strong>Encontre os melhores fornecedores para o seu negócio</strong></h1>
        <p>Conectamos empresas a fornecedores confiáveis em todo o país. Simplifique sua cadeia de suprimentos.</p>
      </div>
      <div class="banner-pesquisa">
        <form class="form-pesquisa">
          <div class="input-icone">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="text" placeholder="O que você está procurando?">
          </div>
          <div class="input-icone">
            <i class="fa-solid fa-location-dot"></i>
            <input type="text" placeholder="Localização">
          </div>
          <button type="submit" class="btn-pesquisar">Buscar</button>
        </form>
      </div>
    </div>
  </section>

  <!-- Categorias -->
  <section class="container-categorias">
    <h2 class="text-center">Categorias Populares</h2>
    <div class="categorias-grid">
      <button class="btn">Todas</button>
      <button class="btn">Alimentos</button>
      <button class="btn">Bebidas</button>
      <button class="btn">Embalagens</button>
      <button class="btn">Limpeza</button>
    </div>
    <div class="categorias-grid cards">
      <div class="mini-card">
        <div class="icone-categoria cor-categoria-hortifruti">
          <i class="fa-solid fa-leaf"></i>
        </div>
        <h3>Hortifrúti</h3>
        <p>Frutas, verduras e legumes frescos</p>
      </div>
      <div class="mini-card">
        <div class="icone-categoria cor-categoria-frios">
          <i class="fa-solid fa-drumstick-bite"></i>
        </div>
        <h3>Carnes e Frios</h3>
        <p>Carnes, queijos e embutidos</p>
      </div>
      <div class="mini-card">
        <div class="icone-categoria cor-categoria-bebidas">
          <i class="fa-solid fa-wine-bottle"></i>
        </div>
        <h3>Bebidas</h3>
        <p>Refrigerantes, cervejas, sucos e água</p>
      </div>
      <div class="mini-card">
        <div class="icone-categoria cor-categoria-embalagens">
          <i class="fa-solid fa-box-open"></i>
        </div>
        <h3>Embalagens</h3>
        <p>Sacos, potes, copos descartáveis e mais</p>
      </div>
    </div>
  </section>

  <!-- Chamada -->
  <section class="gradiant-bg text-white">
    <div class="container-banner">
      <div class="banner-text">
        <h2><strong>Pronto para encontrar os melhores fornecedores?</strong></h2>
        <p>Cadastre-se gratuitamente e comece a simplificar sua cadeia de suprimentos hoje mesmo.</p>
      </div>
      <div class="btns">
        <button class="btn btn-cadastro" onclick="openModal('cadastro')">Cadastre-se</button>
        <button class="btn btn-login" onclick="openModal('login')">Login</button>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer class="footer">
    <div class="caixas">
      <div class="caixa">
        <div class="logo">
          <i class="fa-solid fa-truck-fast text-white caminhao"></i>
          <h1 class="text-white">SupplyLink</h1>
        </div>
        <p>Conectando empresas a fornecedores de qualidade desde 2025.</p>
      </div>
      <div class="caixa">
        <h2>Empresas</h2>
        <p>Como Funciona</p>
        <p>Planos</p>
        <p>Depoimentos</p>
      </div>
      <div class="caixa">
        <h2>Fornecedores</h2>
        <p>Cadastre-se</p>
        <p>Benefícios</p>
        <p>Como Vender Mais</p>
        <p>Dashboard</p>
      </div>
      <div class="caixa">
        <h2>Contato</h2>
        <p><i class="fa-solid fa-envelope"></i> contato@supplylink.com.br</p>
        <p><i class="fa-solid fa-phone"></i> (12) 4002-8922</p>
        <p><i class="fa-solid fa-location-dot"></i> Aparecida - SP, Brasil</p>
      </div>
    </div>
    <hr>
    <div class="text-light-gray lateral">
      <p>&copy; 2025 SupplyLink. Todos os direitos reservados.</p>
      <div class="lateral">
        <p>Termos de Uso</p>
        <p>Política de Privacidade</p>
        <p>Cookies</p>
      </div>
    </div>
  </footer>

  <!-- Modal Cadastro -->
  <div id="modal-cadastro" class="modal">
    <div class="modal-content modal-steps">
      <span class="close" onclick="closeModal('cadastro')">&times;</span>
      <h2>Cadastro</h2>
      
      <form id="form-cadastro" action="php/register.php" method="POST">
        
        <!-- Passo 1 -->
        <div class="form-step active" id="step1">
          <label for="company_name">Nome da Empresa</label>
          <input type="text" name="company_name" id="company_name" required>
          <small class="error"></small>

          <label for="responsible_name">Nome do Responsável</label>
          <input type="text" name="responsible_name" id="responsible_name" required>
          <small class="error"></small>

          <label for="email">E-mail</label>
          <input type="email" name="email" id="email" required>
          <small class="error"></small>

          <label for="phone">Telefone</label>
          <input type="text" name="phone" id="phone" placeholder="(11) 91234-5678" required>
          <small class="error"></small>

          <label for="cpf_cnpj">CPF ou CNPJ</label>
          <input type="text" name="cpf_cnpj" id="cpf_cnpj" placeholder="000.000.000-00 ou 00.000.000/0000-00" required>
          <small class="error" id="cpf_cnpj_error"></small>

          <label for="password">Senha</label>
          <input type="password" name="password" id="password" required>
          <small class="error"></small>

          <label for="confirm_password">Confirme a Senha</label>
          <input type="password" name="confirm_password" id="confirm_password" required>
          <small class="error"></small>

          <button type="button" class="btn btn-cadastro next-btn">Próximo</button>
        </div>

        <!-- Passo 2 -->
        <div class="form-step" id="step2">
          <label for="cep">CEP</label>
          <input type="text" name="cep" id="cep" placeholder="00000-000" required>
          <small class="error"></small>

          <label for="street">Logradouro</label>
          <input type="text" name="street" id="street" required>
          <small class="error"></small>

          <label for="number">Número</label>
          <input type="text" name="number" id="number" required>
          <small class="error"></small>

          <label for="complement">Complemento</label>
          <input type="text" name="complement" id="complement">
          <small class="error"></small>

          <label for="city">Cidade</label>
          <input type="text" name="city" id="city" required>
          <small class="error"></small>

          <label for="state">Estado (UF)</label>
          <input type="text" name="state" id="state" required>
          <small class="error"></small>

          <div class="step-buttons">
            <button type="button" class="btn btn-login prev-btn">Voltar</button>
            <button type="button" class="btn btn-cadastro next-btn">Próximo</button>
          </div>
        </div>

        <!-- Passo 3 -->
        <div class="form-step" id="step3">
          <label for="role">Você está se cadastrando como:</label>
          <select name="role" id="role" required>
            <option value="">Selecione...</option>
            <option value="EMPRESA">Empresa</option>
            <option value="FORNECEDOR">Fornecedor</option>
          </select>
          <small class="error"></small>

          <div class="step-buttons">
            <button type="button" class="btn btn-login prev-btn">Voltar</button>
            <button type="submit" class="btn btn-cadastro">Finalizar</button>
          </div>
        </div>
      </form>
    </div>
  </div>

  <!-- Modal Login -->
  <div id="modal-login" class="modal">
    <div class="modal-content">
      <span class="close" onclick="closeModal('login')">&times;</span>
      <h2>Login</h2>
      <form action="php/login.php" method="POST">
        <label for="email_login">E-mail</label>
        <input type="email" name="email" id="email_login" required>

        <label for="password_login">Senha</label>
        <input type="password" name="password" id="password_login" required>

        <button type="submit" class="btn btn-login">Entrar</button>
      </form>
    </div>
  </div>

  <!-- Scripts -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>

  <script>
    function openModal(type) {
      document.getElementById("modal-" + type).style.display = "flex";
    }
    function closeModal(type) {
      document.getElementById("modal-" + type).style.display = "none";
    }
    window.onclick = function(event) {
      document.querySelectorAll(".modal").forEach(modal => {
        if (event.target === modal) modal.style.display = "none";
      });
    }

    // Máscaras
    $("#phone").mask("(00) 00000-0000");
    $("#cpf_cnpj").mask("000.000.000-00999", {reverse: true});
    $("#cep").mask("00000-000");

    // Multi-step
    const steps = document.querySelectorAll(".form-step");
    let currentStep = 0;

    function showStep(stepIndex) {
      steps.forEach((step, index) => {
        step.classList.toggle("active", index === stepIndex);
      });
      currentStep = stepIndex;
    }

    // --- Validações ---
    function validarEmail(email) {
      return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    }
    function validarCPF(cpf) {
      cpf = cpf.replace(/[^\d]+/g,'');
      if(cpf.length !== 11 || /^(\d)\1+$/.test(cpf)) return false;
      let soma, resto;
      soma = 0;
      for (let i=1; i<=9; i++) soma += parseInt(cpf.substring(i-1, i))*(11-i);
      resto = (soma*10)%11;
      if ((resto==10)||(resto==11)) resto = 0;
      if (resto !== parseInt(cpf.substring(9, 10))) return false;
      soma = 0;
      for (let i=1; i<=10; i++) soma += parseInt(cpf.substring(i-1, i))*(12-i);
      resto = (soma*10)%11;
      if ((resto==10)||(resto==11)) resto = 0;
      return resto === parseInt(cpf.substring(10, 11));
    }
    function validarCNPJ(cnpj) {
      cnpj = cnpj.replace(/[^\d]+/g,'');
      if(cnpj.length !== 14) return false;
      let tamanho = cnpj.length - 2;
      let numeros = cnpj.substring(0,tamanho);
      let digitos = cnpj.substring(tamanho);
      let soma = 0;
      let pos = tamanho - 7;
      for (let i = tamanho; i >= 1; i--) {
        soma += numeros.charAt(tamanho - i) * pos--;
        if (pos < 2) pos = 9;
      }
      let resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
      if (resultado != digitos.charAt(0)) return false;
      tamanho = tamanho + 1;
      numeros = cnpj.substring(0,tamanho);
      soma = 0;
      pos = tamanho - 7;
      for (let i = tamanho; i >= 1; i--) {
        soma += numeros.charAt(tamanho - i) * pos--;
        if (pos < 2) pos = 9;
      }
      resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
      return resultado == digitos.charAt(1);
    }

    function validarStep(stepIndex) {
      let step = steps[stepIndex];
      let inputs = step.querySelectorAll("input, select");
      let valido = true;

      inputs.forEach(input => {
        let errorEl = input.nextElementSibling;
        if (errorEl && errorEl.classList.contains("error")) {
          errorEl.style.display = "none";
          input.classList.remove("invalid");
        }

        if (!input.value.trim()) {
          valido = false;
          if (errorEl) {
            errorEl.textContent = "Campo obrigatório";
            errorEl.style.display = "block";
          }
          input.classList.add("invalid");
        } else if (input.type === "email" && !validarEmail(input.value)) {
          valido = false;
          errorEl.textContent = "E-mail inválido";
          errorEl.style.display = "block";
          input.classList.add("invalid");
        } else if (input.name === "cpf_cnpj") {
          let valor = input.value.replace(/\D/g, "");
          if (valor.length <= 11 && !validarCPF(valor)) {
            valido = false;
            errorEl.textContent = "CPF inválido";
            errorEl.style.display = "block";
            input.classList.add("invalid");
          } else if (valor.length > 11 && !validarCNPJ(valor)) {
            valido = false;
            errorEl.textContent = "CNPJ inválido";
            errorEl.style.display = "block";
            input.classList.add("invalid");
          }
        } else if (input.name === "confirm_password") {
          let senha = document.getElementById("password").value;
          if (senha !== input.value) {
            valido = false;
            errorEl.textContent = "As senhas não coincidem";
            errorEl.style.display = "block";
            input.classList.add("invalid");
          }
        }
      });
      return valido;
    }

    document.querySelectorAll(".next-btn").forEach(btn => {
      btn.addEventListener("click", () => {
        if (validarStep(currentStep)) {
          if (currentStep < steps.length - 1) {
            showStep(currentStep + 1);
          }
        }
      });
    });

    document.querySelectorAll(".prev-btn").forEach(btn => {
      btn.addEventListener("click", () => {
        if (currentStep > 0) {
          showStep(currentStep - 1);
        }
      });
    });

    // ViaCEP
    function buscarCep(cep) {
      cep = cep.replace(/\D/g, "");
      if (cep.length === 8) {
        fetch(`https://viacep.com.br/ws/${cep}/json/`)
          .then(response => response.json())
          .then(data => {
            if (!("erro" in data)) {
              document.getElementById("street").value = data.logradouro;
              document.getElementById("city").value   = data.localidade;
              document.getElementById("state").value  = data.uf;
            } else {
              alert("CEP não encontrado.");
            }
          })
          .catch(() => alert("Erro ao buscar CEP."));
      }
    }
    document.getElementById("cep").addEventListener("blur", () => buscarCep(document.getElementById("cep").value));
  </script>
</body>
</html>
