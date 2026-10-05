<?php

require_once __DIR__ . "/../servicos/ClienteServico.php";

try {

    $clienteServico = new ClienteServico();
    $clientes = $clienteServico->buscar();

    session_start();
    $_SESSION['clientes'] = $clientes;
    $_SESSION['msg'] = $_GET['msg'] ?? null;

    header('Location: ../views/mostrarClientes.php');

} catch (PDOException $erro) {
    echo $erro->getMessage();
}

?>
