CREATE DATABASE IF NOT EXISTS onde_meu_dinheiro_foi
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE onde_meu_dinheiro_foi;

CREATE TABLE IF NOT EXISTS usuarios (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS categorias (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    usuario_id BIGINT UNSIGNED NULL,
    nome VARCHAR(60) NOT NULL,
    tipo ENUM('receita', 'despesa') NOT NULL,
    CONSTRAINT fk_categoria_usuario FOREIGN KEY (usuario_id)
        REFERENCES usuarios(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS movimentacoes (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    usuario_id BIGINT UNSIGNED NOT NULL,
    categoria_id INT UNSIGNED NULL,
    tipo ENUM('receita', 'despesa') NOT NULL,
    descricao VARCHAR(160) NOT NULL,
    valor DECIMAL(12,2) NOT NULL,
    data_movimentacao DATE NOT NULL,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_mov_usuario_data (usuario_id, data_movimentacao),
    CONSTRAINT fk_mov_usuario FOREIGN KEY (usuario_id)
        REFERENCES usuarios(id) ON DELETE CASCADE,
    CONSTRAINT fk_mov_categoria FOREIGN KEY (categoria_id)
        REFERENCES categorias(id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS metas (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    usuario_id BIGINT UNSIGNED NOT NULL,
    titulo VARCHAR(120) NOT NULL,
    valor_objetivo DECIMAL(12,2) NOT NULL,
    valor_guardado DECIMAL(12,2) NOT NULL DEFAULT 0,
    prazo DATE NULL,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_meta_usuario FOREIGN KEY (usuario_id)
        REFERENCES usuarios(id) ON DELETE CASCADE
) ENGINE=InnoDB;

INSERT INTO categorias (usuario_id, nome, tipo)
SELECT NULL, 'Salário', 'receita'
WHERE NOT EXISTS (SELECT 1 FROM categorias WHERE usuario_id IS NULL AND nome='Salário' AND tipo='receita');
INSERT INTO categorias (usuario_id, nome, tipo)
SELECT NULL, 'Renda extra', 'receita'
WHERE NOT EXISTS (SELECT 1 FROM categorias WHERE usuario_id IS NULL AND nome='Renda extra' AND tipo='receita');
INSERT INTO categorias (usuario_id, nome, tipo)
SELECT NULL, 'Alimentação', 'despesa'
WHERE NOT EXISTS (SELECT 1 FROM categorias WHERE usuario_id IS NULL AND nome='Alimentação' AND tipo='despesa');
INSERT INTO categorias (usuario_id, nome, tipo)
SELECT NULL, 'Transporte', 'despesa'
WHERE NOT EXISTS (SELECT 1 FROM categorias WHERE usuario_id IS NULL AND nome='Transporte' AND tipo='despesa');
INSERT INTO categorias (usuario_id, nome, tipo)
SELECT NULL, 'Lazer', 'despesa'
WHERE NOT EXISTS (SELECT 1 FROM categorias WHERE usuario_id IS NULL AND nome='Lazer' AND tipo='despesa');
INSERT INTO categorias (usuario_id, nome, tipo)
SELECT NULL, 'Contas', 'despesa'
WHERE NOT EXISTS (SELECT 1 FROM categorias WHERE usuario_id IS NULL AND nome='Contas' AND tipo='despesa');
