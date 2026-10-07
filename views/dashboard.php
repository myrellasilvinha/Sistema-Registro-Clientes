<?php
require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../conexao.php';

function aniversarioHoje($dataNascimento) {
    if (!$dataNascimento) return false;
    $hoje = date('m-d');
    $nasc = date('m-d', strtotime($dataNascimento));
    return $hoje === $nasc;
}

try {
    $conn = Conexao::criar();
    $stmt = $conn->prepare("SELECT * FROM clientes ORDER BY nome");
    $stmt->execute();
    $clientes = $stmt->fetchAll(PDO::FETCH_OBJ);
} catch (Exception $e) {
    $clientes = array();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Sistema de Clientes</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="icon" href="../img/logo.jpg">
</head>
<body>
<?php require __DIR__ . '/_sidebar.php'; ?>
    <main class="container">
        <div class="page-header">
            <div>
                <h1>Dashboard</h1>
                <p class="subtitle">Visao geral do sistema</p>
            </div>
            <div style="display: flex; gap: 10px;">
                <a href="../views/mostrarClientes.php" class="btn btn-secondary"><span><i class="bi bi-person-lines-fill"></i></span>Ver Clientes</a>
                <a href="../views/formCadastrarCliente.php" class="btn"><span><i class="bi bi-person-plus"></i></span>Novo Cliente</a>
                <!-- <a href="../controladores/authRouter.php?acao=logout" class="btn btn-secondary">Sair</a> -->
            </div>
        </div>
        <div class="cards-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px;">
            <div class="card">
                <h3>Total de Clientes</h3>
                <p style="font-size: 2em; font-weight: bold;"><?php echo count($clientes); ?></p>
            </div>
            <div class="card">
                <h3>Aniversariantes Hoje</h3>
                <p style="font-size: 2em; font-weight: bold;"><?php $cntAniv = 0; foreach ($clientes as $c) { if (aniversarioHoje(isset($c->data_nascimento) ? $c->data_nascimento : null)) $cntAniv++; } echo $cntAniv; ?></p>
            </div>
            <div class="card">
                <h3>Novos no Mes (<?php echo date('m/Y'); ?>)</h3>
                <p style="font-size: 2em; font-weight: bold;"><?php $cntMes = 0; $mes = date('Y-m'); foreach ($clientes as $c) { $cad = isset($c->data_cadastro) ? $c->data_cadastro : ''; if (substr($cad, 0, 7) === $mes) $cntMes++; } echo $cntMes; ?></p>
            </div>
        </div>
        <div class="card">
            <h2>Aniversariantes de Hoje</h2>
            <div class="table-wrapper">
                <table id="tabela-clientes">
                    <thead><tr><th>Nome</th><th>Telefone</th><th>WhatsApp</th></tr></thead>
                    <tbody>
                    <?php foreach ($clientes as $c): ?>
                        <?php $dn = isset($c->data_nascimento) ? $c->data_nascimento : null; if (aniversarioHoje($dn)): ?>
                            <tr>
                                <td class="col-nome"><?php echo htmlspecialchars(isset($c->nome) ? $c->nome : ''); ?></td>
                                <td class="col-telefone"><?php echo htmlspecialchars(isset($c->telefone) ? $c->telefone : ''); ?></td>
                                <td><a href="https://wa.me/<?php echo preg_replace('/\D/', '', isset($c->telefone) ? $c->telefone : ''); ?>" target="_blank" class="link-editar">Enviar Mensagem</a></td>
                            </tr>
                        <?php endif; ?>
                    <?php endforeach; ?>
                    <?php if ($cntAniv === 0): ?>
                        <tr><td colspan="3">Nenhum aniversariante hoje</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</body>
</html>
