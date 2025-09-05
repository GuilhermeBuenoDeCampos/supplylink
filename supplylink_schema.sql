
-- SupplyLink - MySQL Schema
-- Engine: InnoDB, Charset: utf8mb4
-- MySQL 8.0+ recommended

-- 1) Create database
CREATE DATABASE IF NOT EXISTS supplylink
  DEFAULT CHARACTER SET utf8mb4
  DEFAULT COLLATE utf8mb4_unicode_ci;
USE supplylink;

-- 2) Helper tables
CREATE TABLE IF NOT EXISTS product_categories (
  id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(100) NOT NULL UNIQUE,
  slug VARCHAR(120) NOT NULL UNIQUE,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS units_of_measure (
  id TINYINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(50) NOT NULL UNIQUE,
  symbol VARCHAR(15) NOT NULL UNIQUE,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- 3) Accounts (both Empresa and Fornecedor)
CREATE TABLE IF NOT EXISTS accounts (
  id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  role ENUM('EMPRESA','FORNECEDOR') NOT NULL,
  email VARCHAR(190) NOT NULL UNIQUE,
  phone VARCHAR(30) NOT NULL,
  password_hash VARCHAR(255) NOT NULL,
  cpf_cnpj VARCHAR(14) NOT NULL,
  responsible_name VARCHAR(120) NOT NULL,
  company_name VARCHAR(160) NOT NULL,
  cep VARCHAR(8) NOT NULL,
  age TINYINT UNSIGNED NOT NULL,
  state CHAR(2) NOT NULL,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT chk_accounts_role CHECK (role IN ('EMPRESA','FORNECEDOR')),
  CONSTRAINT chk_accounts_cpf_cnpj CHECK (CHAR_LENGTH(cpf_cnpj) IN (11,14)),
  CONSTRAINT chk_accounts_cep CHECK (CHAR_LENGTH(cep) = 8),
  CONSTRAINT chk_accounts_age CHECK (age BETWEEN 16 AND 120)
) ENGINE=InnoDB;

CREATE INDEX idx_accounts_role ON accounts(role);
CREATE INDEX idx_accounts_state ON accounts(state);

-- 4) Products (Fornecedor)
CREATE TABLE IF NOT EXISTS products (
  id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  supplier_id BIGINT UNSIGNED NOT NULL,
  name VARCHAR(160) NOT NULL,
  category_id BIGINT UNSIGNED NOT NULL,
  price DECIMAL(10,2) NOT NULL,
  unit_id TINYINT UNSIGNED NOT NULL,
  description TEXT NULL,
  stock_quantity INT UNSIGNED NOT NULL DEFAULT 0,
  photo_url VARCHAR(255) NULL,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_products_supplier FOREIGN KEY (supplier_id) REFERENCES accounts(id) ON DELETE CASCADE,
  CONSTRAINT fk_products_category FOREIGN KEY (category_id) REFERENCES product_categories(id) ON DELETE RESTRICT,
  CONSTRAINT fk_products_unit FOREIGN KEY (unit_id) REFERENCES units_of_measure(id) ON DELETE RESTRICT,
  CONSTRAINT chk_products_price CHECK (price >= 0),
  CONSTRAINT chk_products_stock CHECK (stock_quantity >= 0)
) ENGINE=InnoDB;

CREATE INDEX idx_products_supplier ON products(supplier_id);
CREATE INDEX idx_products_category ON products(category_id);
CREATE INDEX idx_products_name ON products(name);

-- 5) Carts (Empresa)
CREATE TABLE IF NOT EXISTS carts (
  id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  company_id BIGINT UNSIGNED NOT NULL,
  status ENUM('OPEN','CHECKED_OUT','ABANDONED') NOT NULL DEFAULT 'OPEN',
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_carts_company FOREIGN KEY (company_id) REFERENCES accounts(id) ON DELETE CASCADE,
  CONSTRAINT chk_carts_status CHECK (status IN ('OPEN','CHECKED_OUT','ABANDONED'))
) ENGINE=InnoDB;

CREATE INDEX idx_carts_company_status ON carts(company_id, status);

-- 6) Cart Items (produtos de vários fornecedores no mesmo carrinho)
CREATE TABLE IF NOT EXISTS cart_items (
  id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  cart_id BIGINT UNSIGNED NOT NULL,
  product_id BIGINT UNSIGNED NOT NULL,
  quantity INT UNSIGNED NOT NULL,
  unit_price DECIMAL(10,2) NOT NULL, -- snapshot do preço no momento da adição
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_cart_items_cart FOREIGN KEY (cart_id) REFERENCES carts(id) ON DELETE CASCADE,
  CONSTRAINT fk_cart_items_product FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE RESTRICT,
  CONSTRAINT chk_cart_items_quantity CHECK (quantity > 0),
  CONSTRAINT chk_cart_items_price CHECK (unit_price >= 0),
  UNIQUE KEY uq_cart_product (cart_id, product_id)
) ENGINE=InnoDB;

CREATE INDEX idx_cart_items_cart ON cart_items(cart_id);

-- 7) (Opcional) Orders (caso evolua para pedidos)
CREATE TABLE IF NOT EXISTS orders (
  id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  company_id BIGINT UNSIGNED NOT NULL,
  total DECIMAL(12,2) NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_orders_company FOREIGN KEY (company_id) REFERENCES accounts(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS order_items (
  id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  order_id BIGINT UNSIGNED NOT NULL,
  product_id BIGINT UNSIGNED NOT NULL,
  supplier_id BIGINT UNSIGNED NOT NULL,
  quantity INT UNSIGNED NOT NULL,
  unit_price DECIMAL(10,2) NOT NULL,
  CONSTRAINT fk_order_items_order FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
  CONSTRAINT fk_order_items_product FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE RESTRICT,
  CONSTRAINT fk_order_items_supplier FOREIGN KEY (supplier_id) REFERENCES accounts(id) ON DELETE RESTRICT
) ENGINE=InnoDB;

-- 8) Seed básico
INSERT IGNORE INTO product_categories (name, slug) VALUES
  ('Hortifrúti','hortifruti'),
  ('Carnes e Frios','carnes-frios'),
  ('Bebidas','bebidas'),
  ('Embalagens','embalagens'),
  ('Limpeza','limpeza');

INSERT IGNORE INTO units_of_measure (name, symbol) VALUES
  ('Unidade','un'),
  ('Quilograma','kg'),
  ('Grama','g'),
  ('Litro','L'),
  ('Mililitro','mL'),
  ('Pacote','pct'),
  ('Caixa','cx');
