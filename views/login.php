<?php
require_once __DIR__ . '/../session_safe.php';
$erro = $_SESSION['login_erro'] ?? null;
unset($_SESSION['login_erro']);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistema de Clientes</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="icon" href="../img/logo.jpg">
</head>
<body>
    <main class="container" style="max-width: 500px; margin-top: 60px;">
        <div class="card">
            <h1 style="margin-bottom: 10px;">Login</h1>
            <p class="subtitle" style="margin-bottom: 25px;">Acesse o sistema</p>
            <?php if ($erro): ?>
                <div id="msg-erro">
                    <?php echo htmlspecialchars($erro); ?>
                </div>
            <?php endif; ?>
            <form action="../controladores/authRouter.php?acao=login" method="POST" class="form">
                <div class="form-group">
                    <label for="username">Usuario</label>
                    <input type="text" name="username" id="username" required>
                </div>
                <div class="form-group">
                    <label for="password">Senha</label>
                    <input type="password" name="password" id="password" required>
                </div>
                <div class="form-actions">
                    <button type="submit">Entrar</button>
                </div>
            </form>
            <p style="margin-top: 15px; color: #666; font-size: 0.9rem;">Usuario: admin / Senha: admin123</p>
        </div>
    </main>
</body>
</html>
