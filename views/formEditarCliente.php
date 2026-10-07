<?php
require_once __DIR__ . '/../session_safe.php';
require_once __DIR__ . '/../modelos/Cliente.php';
require_once __DIR__ . '/../auth.php';
require_once __DIR__ . '/../csrf.php';
require_once __DIR__ . '/../servicos/ClienteServico.php';
requireLogin();

$erro = $_SESSION['erro'] ?? null;
unset($_SESSION['erro']);

$cliente = $_SESSION['clienteEditar'] ?? null;
unset($_SESSION['clienteEditar']);

// Quando a tela é aberta pelo botão Editar, carrega o cliente pelo ID.
if (!$cliente instanceof Cliente) {
    $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
    if ($id && $id > 0) {
        try {
            $cliente = (new ClienteServico())->buscarPorId($id);
        } catch (Throwable $e) {
            $erro = $e->getMessage();
        }
    }
}

if (!$cliente instanceof Cliente) {
    $_SESSION['erro'] = $erro ?: 'Não foi possível carregar o cliente para edição.';
    header('Location: ../views/mostrarClientes.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar cliente | Sistema de Clientes</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="icon" href="../img/logo.jpg">
</head>
<body>
<?php require __DIR__ . '/_sidebar.php'; ?>
<main class="container container-form">
    <div class="page-header">
        <div>
            <span class="eyebrow"><i class="bi bi-pencil-square"></i> Clientes</span>
            <h1>Editar cliente</h1>
            <p class="subtitle">Atualize as informações do cliente selecionado.</p>
        </div>
        <a href="../views/mostrarClientes.php" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Voltar
        </a>
    </div>

    <?php if ($erro): ?>
        <div id="msg-erro" role="alert">
            <i class="bi bi-exclamation-circle-fill"></i>
            <span><?= htmlspecialchars($erro, ENT_QUOTES, 'UTF-8') ?></span>
        </div>
    <?php endif; ?>

    <div class="card form-card">
        <div class="card-heading">
            <div class="card-icon"><i class="bi bi-person-vcard"></i></div>
            <div>
                <h2>Dados do cliente</h2>
                <p>Altere os dados e clique em “Salvar alterações”.</p>
            </div>
        </div>

        <form action="../controladores/rota.php?acao=editar" method="POST" id="form-editar" class="form">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(getCsrfToken(), ENT_QUOTES, 'UTF-8') ?>">
            <input type="hidden" name="id" value="<?= (int) $cliente->getId() ?>">
            <input type="hidden" name="status" value="<?= htmlspecialchars($cliente->getStatus() ?: 'ativo', ENT_QUOTES, 'UTF-8') ?>">

            <div class="form-grid">
                <div class="form-group form-group-full">
                    <label for="nome">Nome completo <span>*</span></label>
                    <input type="text" name="nome" id="nome" value="<?= htmlspecialchars($cliente->getNome() ?? '', ENT_QUOTES, 'UTF-8') ?>" maxlength="150" autocomplete="name" required>
                </div>

                <div class="form-group">
                    <label for="email">E-mail <span>*</span></label>
                    <input type="email" name="email" id="email" value="<?= htmlspecialchars($cliente->getEmail() ?? '', ENT_QUOTES, 'UTF-8') ?>" maxlength="150" autocomplete="email" required>
                </div>

                <div class="form-group">
                    <label for="telefone">Telefone <span>*</span></label>
                    <input type="text" name="telefone" id="telefone" value="<?= htmlspecialchars($cliente->getTelefone() ?? '', ENT_QUOTES, 'UTF-8') ?>" maxlength="15" inputmode="numeric" autocomplete="tel" required>
                </div>

                <div class="form-group">
                    <label for="data_nascimento">Data de nascimento</label>
                    <input type="date" name="data_nascimento" id="data_nascimento" value="<?= htmlspecialchars($cliente->getDataNascimento() ?? '', ENT_QUOTES, 'UTF-8') ?>" autocomplete="bday">
                </div>

                <div class="form-group">
                    <label for="endereco">Endereço</label>
                    <input type="text" name="endereco" id="endereco" value="<?= htmlspecialchars($cliente->getEndereco() ?? '', ENT_QUOTES, 'UTF-8') ?>" maxlength="255" autocomplete="street-address">
                </div>

                <div class="form-group form-group-full">
                    <label for="observacoes">Observações</label>
                    <textarea name="observacoes" id="observacoes" rows="4" maxlength="2000" placeholder="Informações adicionais..."><?= htmlspecialchars($cliente->getObservacoes() ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" id="btn-atualizar"><i class="bi bi-check-lg"></i> Salvar alterações</button>
                <a href="../views/mostrarClientes.php" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</main>
<script>
function mascaraTelefone(input) {
    let valor = input.value.replace(/\D/g, '').slice(0, 11);
    if (valor.length > 0) valor = '(' + valor;
    if (valor.length > 3) valor = valor.slice(0, 3) + ') ' + valor.slice(3);
    if (valor.length > 10) valor = valor.slice(0, 10) + '-' + valor.slice(10);
    input.value = valor;
}
const telefoneInput = document.getElementById('telefone');
if (telefoneInput) {
    mascaraTelefone(telefoneInput);
    telefoneInput.addEventListener('input', () => mascaraTelefone(telefoneInput));
}

document.getElementById('form-editar').addEventListener('submit', function () {
    const button = document.getElementById('btn-atualizar');
    button.disabled = true;
    button.innerHTML = '<i class="bi bi-hourglass-split"></i> Salvando...';
});
</script>
</body>
</html>
