<?php
require_once __DIR__ . "/ClienteControlador.php";

$acao = $_GET['acao'] ?? '';

if ($acao == 'salvar') {

    $clienteDTO = new ClienteDTO();
    $clienteDTO->nome = $_POST['nome'];
    $clienteDTO->email = $_POST['email'];
    $clienteDTO->telefone = $_POST['telefone'];

    $clienteControlador = new ClienteControlador();
    $clienteControlador->salvar($clienteDTO);

} else if ($acao == 'editar') {

    $clienteDTO = new ClienteDTO();
    $clienteDTO->id = $_POST['id'];
    $clienteDTO->nome = $_POST['nome'];
    $clienteDTO->email = $_POST['email'];
    $clienteDTO->telefone = $_POST['telefone'];

    $clienteControlador = new ClienteControlador();
    $clienteControlador->editar($clienteDTO);

} else {
    echo "Ação inválida";
}

?>
