<?php
require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../csrf.php';
require_once __DIR__ . '/../servicos/ClienteServico.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../views/mostrarClientes.php');
    exit;
}

if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
    $_SESSION['erro'] = 'Sua sessão expirou. Atualize a página e tente novamente.';
    header('Location: ../views/mostrarClientes.php');
    exit;
}

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

try {
    if (!$id || $id <= 0) {
        throw new Exception('Cliente inválido.');
    }

    (new ClienteServico())->excluir($id);
    header('Location: ../views/mostrarClientes.php?msg=excluido');
    exit;
} catch (Exception $erro) {
    $_SESSION['erro'] = $erro->getMessage();
    header('Location: ../views/mostrarClientes.php');
    exit;
}
