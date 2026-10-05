-- Banco de dados: cadastro_clientes
-- Sistema de Cadastro de Clientes (CRUD completo)

CREATE DATABASE IF NOT EXISTS cadastro_clientes
    DEFAULT CHARACTER SET utf8mb4
    COLLATE utf8mb4_general_ci;

USE cadastro_clientes;

CREATE TABLE IF NOT EXISTS clientes (
    id INT(11) NOT NULL AUTO_INCREMENT,
    nome VARCHAR(150) NOT NULL,
    email VARCHAR(150) NOT NULL,
    telefone VARCHAR(20) NOT NULL,
    data_cadastro DATE NOT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY uk_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Se a tabela já existir, rode este comando para adicionar a restrição de e-mail único:
-- ALTER TABLE clientes ADD UNIQUE KEY uk_email (email);
