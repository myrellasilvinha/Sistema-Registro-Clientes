<?php
    require_once __DIR__ . "/../modelos/Cliente.php";
    session_start();
    $clientes = $_SESSION['clientes'] ?? [];
    $msg = $_SESSION['msg'] ?? null;
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
    <main class="container">
        <div class="page-header">
            <div>
                <h1>Clientes Cadastrados</h1>
                <p class="subtitle">Visualize e gerencie todos os clientes do sistema.</p>
            </div>
            <a href="../views/formCadastrarCliente.php" class="btn">
                <i class="bi bi-plus-lg" style="margin-right: 6px;"></i>
                Novo cliente
            </a>
        </div>

        <?php if ($msg == 'cadastrado'): ?>
            <p id="msg-sucesso">
                <i class="bi bi-check-circle-fill" style="margin-right: 8px;"></i>
                Cliente cadastrado com sucesso!
            </p>
        <?php elseif ($msg == 'editado'): ?>
            <p id="msg-sucesso">
                <i class="bi bi-check-circle-fill" style="margin-right: 8px;"></i>
                Cliente atualizado com sucesso!
            </p>
        <?php elseif ($msg == 'excluido'): ?>
            <p id="msg-sucesso">
                <i class="bi bi-check-circle-fill" style="margin-right: 8px;"></i>
                Cliente excluído com sucesso!
            </p>
        <?php endif; ?>

        <div class="card" style="padding: 0; overflow: hidden;">
            <?php if (empty($clientes)): ?>
                <div class="empty">
                    <strong>Nenhum cliente cadastrado</strong>
                    <span>Clique em "Novo cliente" para adicionar o primeiro.</span>
                </div>
            <?php else: ?>
                <div class="table-wrapper">
                    <table id="tabela-clientes">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>E-mail</th>
                                <th>Telefone</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($clientes as $cliente): ?>
                                <tr>
                                    <td class="col-nome"><?php echo htmlspecialchars($cliente->getNome()); ?></td>
                                    <td class="col-email"><?php echo htmlspecialchars($cliente->getEmail()); ?></td>
                                    <td class="col-telefone"><?php echo htmlspecialchars($cliente->getTelefone()); ?></td>
                                    <td>
                                        <div style="display: flex; gap: 8px;">
                                            <a href="../controladores/editarCliente.php?id=<?php echo $cliente->getId(); ?>" class="link-editar">
                                                <i class="bi bi-pen-fill" style="margin-right: 4px;"></i>
                                                Editar
                                            </a>
                                            <a href="../controladores/excluirCliente.php?id=<?php echo $cliente->getId(); ?>" class="link-excluir" onclick="return confirm('Tem certeza que deseja excluir este cliente?');">
                                                <i class="bi bi-trash-fill" style="margin-right: 4px;"></i>
                                                Excluir
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>

        <a href="../index.html" class="back-link">
            <i class="bi bi-house-door-fill"></i>
            ❮ Voltar para a página inicial
        </a>
    </main>
</body>
</html>
