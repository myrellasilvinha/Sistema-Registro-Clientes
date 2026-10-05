<?php

require_once __DIR__ . "/../servicos/ClienteServico.php";
require_once __DIR__ . "/../ClienteException.php";

$id = $_GET['id'];

try {

    $clienteServico = new ClienteServico();
    $cliente = $clienteServico->buscarPorId($id);

    session_start();
    $_SESSION['clienteEditar'] = $cliente;

    header('Location: ../views/formEditarCliente.php');

} catch (ClienteException $erro) {
    echo $erro->getMessage();
} catch (PDOException $erro) {
    echo $erro->getMessage();
}

?>
