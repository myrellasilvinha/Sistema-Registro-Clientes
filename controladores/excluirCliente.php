<?php

require_once __DIR__ . "/../servicos/ClienteServico.php";

$id = $_GET['id'];

try {

    $clienteServico = new ClienteServico();
    $clienteServico->excluir($id);

    header('Location: buscarClientes.php?msg=excluido');

} catch (PDOException $erro) {
    echo $erro->getMessage();
}

?>
