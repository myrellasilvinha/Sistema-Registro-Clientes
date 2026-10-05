<?php

require_once __DIR__ . "/../modelos/Cliente.php";

class ClienteDAO {

    public function salvar($cliente, $conn) {

        $sql = "INSERT INTO
            clientes(nome, email, telefone, data_cadastro)
            VALUES (?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);
        $stmt->bindValue(1, $cliente->getNome());
        $stmt->bindValue(2, $cliente->getEmail());
        $stmt->bindValue(3, $cliente->getTelefone());
        $stmt->bindValue(4, date('Y-m-d'));

        $stmt->execute();
    }

    public function atualizar($cliente, $conn) {

        $sql = "UPDATE clientes SET
            nome = ?, email = ?, telefone = ?
            WHERE id = ?";

        $stmt = $conn->prepare($sql);
        $stmt->bindValue(1, $cliente->getNome());
        $stmt->bindValue(2, $cliente->getEmail());
        $stmt->bindValue(3, $cliente->getTelefone());
        $stmt->bindValue(4, $cliente->getId());

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

        $clientesModelo = [];
        foreach ($clientes as $cliente) {
            array_push($clientesModelo, $this->converterParaModelo($cliente));
        }

        return $clientesModelo;
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
        // Compara apenas os dígitos, ignorando máscara
        $digitos = preg_replace('/\D/', '', $telefone);

        $sql = "SELECT id, telefone FROM clientes";
        if ($idExcluir !== null) {
            $sql .= " WHERE id != ?";
        }

        $stmt = $conn->prepare($sql);
        if ($idExcluir !== null) {
            $stmt->bindValue(1, $idExcluir);
        }
        $stmt->execute();
        $registros = $stmt->fetchAll(PDO::FETCH_OBJ);

        foreach ($registros as $registro) {
            $digitosExistente = preg_replace('/\D/', '', $registro->telefone);
            if ($digitosExistente === $digitos && $digitos !== '') {
                return true;
            }
        }

        return false;
    }

    private function converterParaModelo($registro) {
        $clienteModelo = new Cliente();
        $clienteModelo->setId($registro->id);
        $clienteModelo->setNome($registro->nome);
        $clienteModelo->setEmail($registro->email);
        $clienteModelo->setTelefone($registro->telefone);
        $clienteModelo->setDataCadastro($registro->data_cadastro);
        return $clienteModelo;
    }
}

?>
