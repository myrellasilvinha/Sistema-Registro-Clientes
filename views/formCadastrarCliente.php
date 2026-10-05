<?php
    session_start();
    $erro = $_SESSION['erro'] ?? null;
    $formNome = $_SESSION['form_nome'] ?? '';
    $formEmail = $_SESSION['form_email'] ?? '';
    $formTelefone = $_SESSION['form_telefone'] ?? '';
    unset($_SESSION['erro'], $_SESSION['form_nome'], $_SESSION['form_email'], $_SESSION['form_telefone']);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Cliente</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="icon" href="../img/logo.jpg">
</head>
<body>
    <main class="container">
        <div class="page-header">
            <div>
                <h1>Cadastrar Cliente</h1>
                <p class="subtitle">Preencha os dados abaixo para adicionar um novo cliente.</p>
            </div>
        </div>

        <?php if ($erro): ?>
            <div id="msg-erro">
                <i class="bi bi-exclamation-circle-fill" style="margin-right: 8px;"></i>
                <?php echo htmlspecialchars($erro); ?>
            </div>
        <?php endif; ?>

        <div class="card">
            <form action="../controladores/rota.php?acao=salvar" method="POST" id="form-cadastrar" class="form">
                <div class="form-group">
                    <label for="nome">Nome</label>
                    <input type="text" name="nome" id="nome" placeholder="Digite o nome completo" value="<?php echo htmlspecialchars($formNome); ?>" required>
                </div>

                <div class="form-group">
                    <label for="email">E-mail</label>
                    <input type="email" name="email" id="email" placeholder="exemplo@email.com" value="<?php echo htmlspecialchars($formEmail); ?>" required>
                </div>

                <div class="form-group">
                    <label for="telefone">Telefone</label>
                    <input type="text" name="telefone" id="telefone" placeholder="(68) 99999-9999" maxlength="15" inputmode="numeric" value="<?php echo htmlspecialchars($formTelefone); ?>">
                </div>

                <div class="form-actions">
                    <button type="submit" id="btn-salvar">
                        Salvar
                    </button>
                    <a href="../index.html" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>

        <a href="../index.html" class="back-link">
            <i class="bi bi-house-door-fill"></i>
            ❮ Voltar para a página inicial
        </a>
    </main>

    <script>
        function mascaraTelefone(input) {
            let valor = input.value.replace(/\D/g, '');

            if (valor.length > 11) {
                valor = valor.slice(0, 11);
            }

            if (valor.length > 0) {
                valor = '(' + valor;
            }
            if (valor.length > 3) {
                valor = valor.slice(0, 3) + ') ' + valor.slice(3);
            }
            if (valor.length > 10) {
                valor = valor.slice(0, 10) + '-' + valor.slice(10);
            }

            input.value = valor;
        }

        const telefoneInput = document.getElementById('telefone');
        if (telefoneInput.value) {
            mascaraTelefone(telefoneInput);
        }
        telefoneInput.addEventListener('input', function () {
            mascaraTelefone(this);
        });
    </script>
</body>
</html>
