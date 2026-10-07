<?php
require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../servicos/ClienteServico.php';
require_once __DIR__ . '/../conexao.php';

try {
    $clienteServico = new ClienteServico();
    $clientes = $clienteServico->buscar();
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=clientes_' . date('Y-m-d_H-i-s') . '.csv');
    $output = fopen('php://output', 'w');
    fputcsv($output, array('ID', 'Nome', 'Email', 'Telefone', 'Data_Nascimento', 'Endereco', 'Observacoes', 'Data_Cadastro'));
    foreach ($clientes as $cliente) {
        fputcsv($output, array(
            $cliente->getId(),
            $cliente->getNome(),
            $cliente->getEmail(),
            $cliente->getTelefone(),
            $cliente->getDataNascimento(),
            $cliente->getEndereco(),
            $cliente->getObservacoes(),
            $cliente->getDataCadastro()
        ));
    }
    fclose($output);
    exit;
} catch (Exception $e) {
    header('Location: ../views/mostrarClientes.php');
    exit;
}
