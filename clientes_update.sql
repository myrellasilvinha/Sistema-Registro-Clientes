-- Migração para instalações que já possuem a tabela clientes antiga.
-- Execute este arquivo somente se o banco já tiver sido criado pelo schema antigo.

USE cadastro_clientes;

ALTER TABLE clientes ADD COLUMN IF NOT EXISTS data_nascimento DATE NULL;
ALTER TABLE clientes ADD COLUMN IF NOT EXISTS endereco VARCHAR(255) NULL;
ALTER TABLE clientes ADD COLUMN IF NOT EXISTS observacoes TEXT NULL;
ALTER TABLE clientes ADD COLUMN IF NOT EXISTS tags VARCHAR(255) NULL;
ALTER TABLE clientes ADD COLUMN IF NOT EXISTS status VARCHAR(50) NOT NULL DEFAULT 'ativo';
ALTER TABLE clientes ADD COLUMN IF NOT EXISTS telefone_digits VARCHAR(20) NULL;

UPDATE clientes
SET telefone_digits = REGEXP_REPLACE(telefone, '[^0-9]', '')
WHERE telefone_digits IS NULL OR telefone_digits = '';

-- Antes de executar a linha abaixo, verifique se não existem telefones duplicados.
ALTER TABLE clientes ADD UNIQUE KEY uk_telefone_digits (telefone_digits);
