<?php
    require_once __DIR__ . "/../modelos/Cliente.php";
    session_start();
    $cliente = $_SESSION['clienteEditar'];
    $erro = $_SESSION['erro'] ?? null;
    unset($_SESSION['erro']);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Cliente</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="icon" href="../img/logo.jpg">
</head>
<body>
    <main class="container">
        <div class="page-header">
            <div>
                <h1>Editar Cliente</h1>
                <p class="subtitle">Atualize as informações do cliente selecionado.</p>
            </div>
        </div>

        <?php if ($erro): ?>
            <div id="msg-erro">
                <i class="bi bi-exclamation-circle-fill" style="margin-right: 8px;"></i>
                <?php echo htmlspecialchars($erro); ?>
            </div>
        <?php endif; ?>

        <div class="card">
            <form action="../controladores/rota.php?acao=editar" method="POST" id="form-editar" class="form">
                <input type="hidden" name="id" value="<?php echo $cliente->getId(); ?>">

                <div class="form-group">
                    <label for="nome">Nome</label>
                    <input type="text" name="nome" id="nome" value="<?php echo htmlspecialchars($cliente->getNome()); ?>" placeholder="Digite o nome completo" required>
                </div>

                <div class="form-group">
                    <label for="email">E-mail</label>
                    <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($cliente->getEmail()); ?>" placeholder="exemplo@email.com" required>
                </div>

                <div class="form-group">
                    <label for="telefone">Telefone</label>
                    <input type="text" name="telefone" id="telefone" value="<?php echo htmlspecialchars($cliente->getTelefone()); ?>" placeholder="(68) 99999-9999" maxlength="15" inputmode="numeric">
                </div>

                <div class="form-actions">
                    <button type="submit" id="btn-atualizar">
                        <!-- <i class="bi bi-check-lg" style="margin-right: 6px;"></i> -->
                        Atualizar
                    </button>
                    <a href="../controladores/buscarClientes.php" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>

        <a href="../controladores/buscarClientes.php" class="back-link">
            <i class="bi bi-house-door-fill"></i>
            ❮ Voltar para a lista
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

        // Aplica a máscara ao carregar (caso o valor já exista)
        mascaraTelefone(telefoneInput);

        telefoneInput.addEventListener('input', function () {
            mascaraTelefone(this);
        });
    </script>
</body>
</html>
