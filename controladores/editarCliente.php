<?php
require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../servicos/ClienteServico.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id || $id <= 0) {
    $_SESSION['erro'] = 'Cliente inválido para edição.';
    header('Location: ../views/mostrarClientes.php');
    exit;
}

try {
    // Não dependemos somente da sessão: passamos o ID para a tela de edição.
    // Isso evita perder o cliente selecionado durante o redirecionamento.
    (new ClienteServico())->buscarPorId($id);
    header('Location: ../views/formEditarCliente.php?id=' . $id);
    exit;
} catch (Throwable $erro) {
    $_SESSION['erro'] = $erro->getMessage();
    header('Location: ../views/mostrarClientes.php');
    exit;
}
