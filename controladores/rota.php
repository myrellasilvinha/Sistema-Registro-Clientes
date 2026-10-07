<?php
require_once __DIR__ . '/../session_safe.php';
require_once __DIR__ . '/../auth.php';
require_once __DIR__ . '/../csrf.php';
requireLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../views/mostrarClientes.php');
    exit;
}

if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
    $_SESSION['erro'] = 'Sua sessão expirou. Atualize a página e tente novamente.';
    header('Location: ../views/mostrarClientes.php');
    exit;
}

require_once __DIR__ . '/ClienteControlador.php';
require_once __DIR__ . '/../DTOs/ClienteDTO.php';

$acao = $_GET['acao'] ?? '';

$clienteDTO = new ClienteDTO();
$clienteDTO->id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT) ?: null;
$clienteDTO->nome = trim($_POST['nome'] ?? '');
$clienteDTO->email = trim($_POST['email'] ?? '');
$clienteDTO->telefone = trim($_POST['telefone'] ?? '');
$clienteDTO->data_nascimento = ($_POST['data_nascimento'] ?? '') !== '' ? $_POST['data_nascimento'] : null;
$clienteDTO->endereco = trim($_POST['endereco'] ?? '');
$clienteDTO->observacoes = trim($_POST['observacoes'] ?? '');
$clienteDTO->status = $_POST['status'] ?? 'ativo';

$clienteControlador = new ClienteControlador();

try {
    switch ($acao) {
        case 'salvar':
            $clienteControlador->salvar($clienteDTO);
            break;
        case 'editar':
            $clienteControlador->editar($clienteDTO);
            break;
        default:
            http_response_code(400);
            echo 'Ação inválida.';
            exit;
    }
} catch (Throwable $erro) {
    $_SESSION['erro'] = $erro->getMessage();
    if ($acao === 'editar') {
        $_SESSION['clienteEditar'] = $clienteControlador->criarModeloParaFormulario($clienteDTO);
        header('Location: ../views/formEditarCliente.php?id=' . (int)$clienteDTO->id);
    } else {
        header('Location: ../views/formCadastrarCliente.php');
    }
    exit;
}
