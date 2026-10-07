-- Banco de dados: cadastro_clientes
-- Sistema de Registro de Clientes - schema completo

CREATE DATABASE IF NOT EXISTS cadastro_clientes
    DEFAULT CHARACTER SET utf8mb4
    COLLATE utf8mb4_general_ci;

USE cadastro_clientes;

CREATE TABLE IF NOT EXISTS clientes (
    id INT NOT NULL AUTO_INCREMENT,
    nome VARCHAR(150) NOT NULL,
    email VARCHAR(150) NOT NULL,
    telefone VARCHAR(20) NOT NULL,
    telefone_digits VARCHAR(20) NOT NULL,
    data_nascimento DATE NULL,
    endereco VARCHAR(255) NULL,
    observacoes TEXT NULL,
    tags VARCHAR(255) NULL,
    data_cadastro DATE NOT NULL,
    status VARCHAR(50) NOT NULL DEFAULT 'ativo',
    PRIMARY KEY (id),
    UNIQUE KEY uk_email (email),
    UNIQUE KEY uk_telefone_digits (telefone_digits),
    KEY idx_nome (nome),
    KEY idx_data_nascimento (data_nascimento)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
