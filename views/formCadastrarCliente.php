<?php
require_once __DIR__ . '/../session_safe.php';
require_once __DIR__ . '/../auth.php';
require_once __DIR__ . '/../csrf.php';
requireLogin();

$erro = $_SESSION['erro'] ?? null;
$formNome = $_SESSION['form_nome'] ?? '';
$formEmail = $_SESSION['form_email'] ?? '';
$formTelefone = $_SESSION['form_telefone'] ?? '';
$formDataNascimento = $_SESSION['form_data_nascimento'] ?? '';
$formEndereco = $_SESSION['form_endereco'] ?? '';
$formObservacoes = $_SESSION['form_observacoes'] ?? '';
unset(
    $_SESSION['erro'],
    $_SESSION['form_nome'],
    $_SESSION['form_email'],
    $_SESSION['form_telefone'],
    $_SESSION['form_data_nascimento'],
    $_SESSION['form_endereco'],
    $_SESSION['form_observacoes']
);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar cliente | Sistema de Clientes</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="icon" href="../img/logo.jpg">
</head>
<body>
<?php require __DIR__ . '/_sidebar.php'; ?>
<main class="container container-form">
    <div class="page-header">
        <div>
            <span class="eyebrow"><i class="bi bi-person-plus"></i> Clientes</span>
            <h1>Cadastrar cliente</h1>
            <p class="subtitle">Preencha os dados abaixo para adicionar um novo cliente.</p>
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
                <p>Os campos marcados com <strong>*</strong> são obrigatórios.</p>
            </div>
        </div>

        <form action="../controladores/rota.php?acao=salvar" method="POST" id="form-cadastrar" class="form" novalidate>
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(getCsrfToken(), ENT_QUOTES, 'UTF-8') ?>">
            <div class="form-grid">
                <div class="form-group form-group-full">
                    <label for="nome">Nome completo <span>*</span></label>
                    <input type="text" name="nome" id="nome" placeholder="Ex.: Maria da Silva" value="<?= htmlspecialchars($formNome, ENT_QUOTES, 'UTF-8') ?>" maxlength="150" autocomplete="name" required>
                </div>

                <div class="form-group">
                    <label for="email">E-mail <span>*</span></label>
                    <input type="email" name="email" id="email" placeholder="exemplo@email.com" value="<?= htmlspecialchars($formEmail, ENT_QUOTES, 'UTF-8') ?>" maxlength="150" autocomplete="email" required>
                </div>

                <div class="form-group">
                    <label for="telefone">Telefone <span>*</span></label>
                    <input type="text" name="telefone" id="telefone" placeholder="(68) 99999-9999" value="<?= htmlspecialchars($formTelefone, ENT_QUOTES, 'UTF-8') ?>" maxlength="15" inputmode="numeric" autocomplete="tel" required>
                </div>

                <div class="form-group">
                    <label for="data_nascimento">Data de nascimento</label>
                    <input type="date" name="data_nascimento" id="data_nascimento" value="<?= htmlspecialchars($formDataNascimento, ENT_QUOTES, 'UTF-8') ?>" autocomplete="bday">
                </div>

                <div class="form-group">
                    <label for="endereco">Endereço</label>
                    <input type="text" name="endereco" id="endereco" placeholder="Rua, número, bairro..." value="<?= htmlspecialchars($formEndereco, ENT_QUOTES, 'UTF-8') ?>" maxlength="255" autocomplete="street-address">
                </div>

                <div class="form-group form-group-full">
                    <label for="observacoes">Observações</label>
                    <textarea name="observacoes" id="observacoes" rows="4" maxlength="2000" placeholder="Informações adicionais sobre o cliente..."><?= htmlspecialchars($formObservacoes, ENT_QUOTES, 'UTF-8') ?></textarea>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" id="btn-salvar"><i class="bi bi-check-lg"></i> Salvar cliente</button>
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
mascaraTelefone(telefoneInput);
telefoneInput.addEventListener('input', () => mascaraTelefone(telefoneInput));
</script>
</body>
</html>
