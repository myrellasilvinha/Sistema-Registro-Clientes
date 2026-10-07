<?php
require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../modelos/Cliente.php';
require_once __DIR__ . '/../servicos/ClienteServico.php';
require_once __DIR__ . '/../csrf.php';
$msg = isset($_GET['msg']) ? $_GET['msg'] : null;
$busca = isset($_GET['busca']) ? $_GET['busca'] : '';
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$porPagina = isset($_GET['por_pagina']) ? (int)$_GET['por_pagina'] : 20;
if ($porPagina < 10) { $porPagina = 10; }
if ($porPagina > 100) { $porPagina = 100; }
if ($pagina < 1) { $pagina = 1; }
try {
    $clienteServico = new ClienteServico();
    $resultado = $clienteServico->buscarComFiltro($busca, $pagina, $porPagina);
    $clientes = $resultado['clientes'];
    $total = $resultado['total'];
} catch (Exception $e) {
    $clientes = array();
    $total = 0;
}
$totalPaginas = ceil($total / $porPagina);
if ($totalPaginas < 1) { $totalPaginas = 1; }
if ($pagina > $totalPaginas) { $pagina = $totalPaginas; }
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Clientes</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="icon" href="../img/logo.jpg">
</head>
<body>
<?php require __DIR__ . '/_sidebar.php'; ?>
    <main class="container">
        <div class="page-header">
            <div>
                <h1>Clientes Cadastrados</h1>
                <p class="subtitle">Visualize e gerencie todos os clientes do sistema.</p>
            </div>
            <div class="header-actions">
                <a href="../views/formCadastrarCliente.php" class="btn"><i class="bi bi-plus-lg" style="margin-right: 6px;"></i>Novo cliente</a>
                <a href="../controladores/exportarClientes.php" class="btn btn-secondary"><i class="bi bi-download" style="margin-right: 6px;"></i>Exportar CSV</a>
                <!-- <a href="../controladores/authRouter.php?acao=logout" class="btn btn-secondary">Sair</a> -->
            </div>
        </div>
        <?php if ($msg === 'cadastrado'): ?>
            <p id="msg-sucesso"><i class="bi bi-check-circle-fill" style="margin-right: 8px;"></i>Cliente cadastrado com sucesso!</p>
        <?php elseif ($msg === 'editado'): ?>
            <p id="msg-sucesso"><i class="bi bi-check-circle-fill" style="margin-right: 8px;"></i>Cliente atualizado com sucesso!</p>
        <?php elseif ($msg === 'excluido'): ?>
            <p id="msg-sucesso"><i class="bi bi-check-circle-fill" style="margin-right: 8px;"></i>Cliente excluido com sucesso!</p>
        <?php endif; ?>
        <div class="card">
            <form method="GET" action="../views/mostrarClientes.php" class="search-form">
                <input class="search-input" type="text" name="busca" id="busca" placeholder="Buscar por nome, e-mail ou telefone..." value="<?php echo htmlspecialchars($busca, ENT_QUOTES, 'UTF-8'); ?>">
                <!-- <select class="page-size" name="por_pagina">
                    <option value="10" <?php if ($porPagina == 10) echo 'selected'; ?>>10</option>
                    <option value="20" <?php if ($porPagina == 20) echo 'selected'; ?>>20</option>
                    <option value="50" <?php if ($porPagina == 50) echo 'selected'; ?>>50</option>
                </select> -->
                <button type="submit" id="btn-buscar">Buscar</button>
            </form>
            <?php if (empty($clientes)): ?>
                <div class="empty"><strong>Nenhum cliente encontrado</strong></div>
            <?php else: ?>
                <div class="table-wrapper">
                    <table id="tabela-clientes">
                        <thead><tr><th>Nome</th><th>E-mail</th><th>Telefone</th><th>Aniversario</th><th>Acoes</th></tr></thead>
                        <tbody>
                        <?php foreach ($clientes as $cliente): ?>
                            <?php $telefone = htmlspecialchars($cliente->getTelefone()); $telefoneDigitos = preg_replace('/\D/', '', $cliente->getTelefone()); ?>
                            <tr>
                                <td class="col-nome"><?php echo htmlspecialchars($cliente->getNome(), ENT_QUOTES, 'UTF-8'); ?></td>
                                <td class="col-email"><?php echo htmlspecialchars($cliente->getEmail(), ENT_QUOTES, 'UTF-8'); ?></td>
                                <td class="col-telefone"><?php echo $telefone; ?></td>
                                <td><?php echo $cliente->getDataNascimento() ? date('d/m/Y', strtotime($cliente->getDataNascimento())) : '-'; ?></td>
                                <td>
                                    <div class="row-actions">
                                        <a style="gap: 8px;" href="../controladores/editarCliente.php?id=<?php echo $cliente->getId(); ?>" class="link-editar"><span><i class="bi bi-pen"></i></span>Editar</a>
                                        <!-- <a href="https://wa.me/<?php echo $telefoneDigitos; ?>" target="_blank" class="link-editar"><span><i class="bi bi-whatsapp"></i></span>WhatsApp</a> -->
                                        <form action="../controladores/excluirCliente.php" method="POST" class="delete-form" onsubmit="return confirm('Tem certeza que deseja excluir este cliente?');">
                                            <input type="hidden" name="csrf_token" value="<?php echo getCsrfToken(); ?>">
                                            <input type="hidden" name="id" value="<?php echo $cliente->getId(); ?>">
                                            <button type="submit" class="link-excluir"><span><i class="bi bi-trash3"></i></span>Excluir</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="pagination-bar">
                    <div>Total: <?php echo $total; ?> clientes</div>
                    <div class="pagination-actions">
                        <?php if ($pagina > 1): ?>
                            <a href="?busca=<?php echo urlencode($busca); ?>&pagina=<?php echo ($pagina-1); ?>&por_pagina=<?php echo $porPagina; ?>" class="btn btn-secondary">Anterior</a>
                        <?php endif; ?>
                        <span style="padding: 8px 12px;">Pagina <?php echo $pagina; ?> de <?php echo $totalPaginas; ?></span>
                        <?php if ($pagina < $totalPaginas): ?>
                            <a href="?busca=<?php echo urlencode($busca); ?>&pagina=<?php echo ($pagina+1); ?>&por_pagina=<?php echo $porPagina; ?>" class="btn btn-secondary">Proxima</a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>
