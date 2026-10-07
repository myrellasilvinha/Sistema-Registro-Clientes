<?php

require_once __DIR__ . '/../DAOs/ClienteDAO.php';
require_once __DIR__ . '/../conexao.php';
require_once __DIR__ . '/../ClienteException.php';

class ClienteServico {

    public function salvar($cliente) {
        $this->validar($cliente);
        $conn = Conexao::criar();
        $clienteDAO = new ClienteDAO();
        $this->validarUnicidade($cliente, $clienteDAO, $conn);
        $clienteDAO->salvar($cliente, $conn);
    }

    public function editar($cliente) {
        if (!$cliente->getId()) {
            throw new ClienteException("Cliente invalido para edicao");
        }
        $this->validar($cliente);
        $conn = Conexao::criar();
        $clienteDAO = new ClienteDAO();
        $this->validarUnicidade($cliente, $clienteDAO, $conn, $cliente->getId());
        $clienteDAO->atualizar($cliente, $conn);
    }

    public function excluir($id) {
        $conn = Conexao::criar();
        $clienteDAO = new ClienteDAO();
        $clienteDAO->excluir($id, $conn);
    }

    public function buscar() {
        $conn = Conexao::criar();
        $clienteDAO = new ClienteDAO();
        return $clienteDAO->buscar($conn);
    }

    public function buscarComFiltro($busca, $pagina, $porPagina) {
        $conn = Conexao::criar();
        $clienteDAO = new ClienteDAO();
        return $clienteDAO->buscarComFiltro($busca, $pagina, $porPagina, $conn);
    }

    public function buscarPorId($id) {
        $conn = Conexao::criar();
        $clienteDAO = new ClienteDAO();
        $cliente = $clienteDAO->buscarPorId($id, $conn);
        if (!$cliente) {
            throw new ClienteException("Cliente nao encontrado");
        }
        return $cliente;
    }

    private function validar($cliente) {
        if (empty(trim($cliente->getNome()))) {
            throw new ClienteException("Campo nome obrigatorio");
        }
        if (!filter_var($cliente->getEmail(), FILTER_VALIDATE_EMAIL)) {
            throw new ClienteException("Campo e-mail invalido");
        }
        if (empty(trim($cliente->getTelefone()))) {
            throw new ClienteException("Campo telefone obrigatorio");
        }
        $digitosTelefone = preg_replace('/\D/', '', $cliente->getTelefone());
        if (strlen($digitosTelefone) < 10 || strlen($digitosTelefone) > 11) {
            throw new ClienteException("Telefone deve ter DDD + numero (10 ou 11 digitos)");
        }
    }

    private function validarUnicidade($cliente, $clienteDAO, $conn, $idExcluir = null) {
        if ($clienteDAO->emailExiste($cliente->getEmail(), $conn, $idExcluir)) {
            throw new ClienteException("Este e-mail ja esta cadastrado no sistema");
        }
        if ($clienteDAO->telefoneExiste($cliente->getTelefone(), $conn, $idExcluir)) {
            throw new ClienteException("Este telefone ja esta cadastrado no sistema");
        }
    }
}
