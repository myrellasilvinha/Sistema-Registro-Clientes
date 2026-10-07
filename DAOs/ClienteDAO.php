<?php

require_once __DIR__ . '/../modelos/Cliente.php';

class ClienteDAO {

    public function salvar($cliente, $conn) {
        $sql = "INSERT INTO clientes(nome, email, telefone, telefone_digits, data_nascimento, endereco, observacoes, data_cadastro, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(1, $cliente->getNome());
        $stmt->bindValue(2, $cliente->getEmail());
        $stmt->bindValue(3, $cliente->getTelefone());
        $stmt->bindValue(4, preg_replace('/\D/', '', $cliente->getTelefone()));
        $stmt->bindValue(5, $cliente->getDataNascimento() ?: null);
        $stmt->bindValue(6, $cliente->getEndereco());
        $stmt->bindValue(7, $cliente->getObservacoes());
        $stmt->bindValue(8, date('Y-m-d'));
        $stmt->bindValue(9, $cliente->getStatus() ?: 'ativo');
        $stmt->execute();
    }

    public function atualizar($cliente, $conn) {
        $sql = "UPDATE clientes SET nome = ?, email = ?, telefone = ?, telefone_digits = ?, data_nascimento = ?, endereco = ?, observacoes = ?, status = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(1, $cliente->getNome());
        $stmt->bindValue(2, $cliente->getEmail());
        $stmt->bindValue(3, $cliente->getTelefone());
        $stmt->bindValue(4, preg_replace('/\D/', '', $cliente->getTelefone()));
        $stmt->bindValue(5, $cliente->getDataNascimento() ?: null);
        $stmt->bindValue(6, $cliente->getEndereco());
        $stmt->bindValue(7, $cliente->getObservacoes());
        $stmt->bindValue(8, $cliente->getStatus() ?: 'ativo');
        $stmt->bindValue(9, $cliente->getId());
        $stmt->execute();
    }

    public function excluir($id, $conn) {
        $sql = "DELETE FROM clientes WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(1, $id);
        $stmt->execute();
    }

    public function buscar($conn) {
        $sql = "SELECT * FROM clientes ORDER BY nome";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $clientes = $stmt->fetchAll(PDO::FETCH_OBJ);
        $clientesModelo = array();
        foreach ($clientes as $cliente) {
            array_push($clientesModelo, $this->converterParaModelo($cliente));
        }
        return $clientesModelo;
    }

    public function buscarComFiltro($busca, $pagina, $porPagina, $conn) {
        $offset = ($pagina - 1) * $porPagina;
        if (!empty($busca)) {
            $buscaLike = '%' . $busca . '%';
            $sqlCount = "SELECT COUNT(*) FROM clientes WHERE nome LIKE ? OR email LIKE ? OR telefone LIKE ? OR telefone_digits LIKE ?";
            $stmtCount = $conn->prepare($sqlCount);
            $stmtCount->bindParam(1, $buscaLike);
            $stmtCount->bindParam(2, $buscaLike);
            $stmtCount->bindParam(3, $buscaLike);
            $stmtCount->bindParam(4, $buscaLike);
            $stmtCount->execute();
            $total = $stmtCount->fetchColumn();
            $sql = "SELECT * FROM clientes WHERE nome LIKE ? OR email LIKE ? OR telefone LIKE ? OR telefone_digits LIKE ? ORDER BY nome LIMIT ? OFFSET ?";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(1, $buscaLike);
            $stmt->bindParam(2, $buscaLike);
            $stmt->bindParam(3, $buscaLike);
            $stmt->bindParam(4, $buscaLike);
            $stmt->bindParam(5, $porPagina, PDO::PARAM_INT);
            $stmt->bindParam(6, $offset, PDO::PARAM_INT);
        } else {
            $sqlCount = "SELECT COUNT(*) FROM clientes";
            $stmtCount = $conn->prepare($sqlCount);
            $stmtCount->execute();
            $total = $stmtCount->fetchColumn();
            $sql = "SELECT * FROM clientes ORDER BY nome LIMIT ? OFFSET ?";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(1, $porPagina, PDO::PARAM_INT);
            $stmt->bindParam(2, $offset, PDO::PARAM_INT);
        }
        $stmt->execute();
        $clientes = $stmt->fetchAll(PDO::FETCH_OBJ);
        $clientesModelo = array();
        foreach ($clientes as $cliente) {
            array_push($clientesModelo, $this->converterParaModelo($cliente));
        }
        return array('clientes' => $clientesModelo, 'total' => (int)$total);
    }
    public function buscarPorId($id, $conn) {
        $sql = "SELECT * FROM clientes WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        $cliente = $stmt->fetch(PDO::FETCH_OBJ);
        if (!$cliente) {
            return null;
        }
        return $this->converterParaModelo($cliente);
    }

    public function emailExiste($email, $conn, $idExcluir = null) {
        $sql = "SELECT id FROM clientes WHERE LOWER(email) = LOWER(?)";
        if ($idExcluir !== null) {
            $sql .= " AND id != ?";
        }
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(1, trim($email));
        if ($idExcluir !== null) {
            $stmt->bindValue(2, $idExcluir);
        }
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ) !== false;
    }

    public function telefoneExiste($telefone, $conn, $idExcluir = null) {
        $digitos = preg_replace('/\D/', '', $telefone);
        if ($digitos === '') {
            return false;
        }
        $sql = "SELECT id FROM clientes WHERE telefone_digits = ?";
        if ($idExcluir !== null) {
            $sql .= " AND id != ?";
        }
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(1, $digitos);
        if ($idExcluir !== null) {
            $stmt->bindValue(2, $idExcluir);
        }
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ) !== false;
    }

    public function telefoneExistePorDigitos($digitos, $conn, $idExcluir = null) {
        if (empty($digitos)) return false;
        $sql = "SELECT id FROM clientes WHERE telefone_digits = ?";
        if ($idExcluir !== null) $sql .= " AND id != ?";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(1, $digitos);
        if ($idExcluir !== null) $stmt->bindValue(2, $idExcluir);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ) !== false;
    }

    private function converterParaModelo($registro) {
        $clienteModelo = new Cliente();
        $clienteModelo->setId($registro->id);
        $clienteModelo->setNome($registro->nome);
        $clienteModelo->setEmail($registro->email);
        $clienteModelo->setTelefone($registro->telefone);
        $clienteModelo->setDataCadastro($registro->data_cadastro);
        $clienteModelo->setDataNascimento($registro->data_nascimento ?? null);
        $clienteModelo->setEndereco($registro->endereco ?? null);
        $clienteModelo->setObservacoes($registro->observacoes ?? null);
        $clienteModelo->setTags($registro->tags ?? null);
        $clienteModelo->setStatus($registro->status ?? 'ativo');
        return $clienteModelo;
    }
}

