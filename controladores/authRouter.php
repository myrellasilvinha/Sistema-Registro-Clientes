<?php
require_once __DIR__ . '/../controladores/AuthController.php';
require_once __DIR__ . '/../session_safe.php';

$acao = $_GET['acao'] ?? '';

if ($acao === 'login') {
    try {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        $auth = new AuthController();
        $auth->login($username, $password);
        header('Location: ../views/dashboard.php');
        exit;
    } catch (Exception $e) {
        $_SESSION['login_erro'] = $e->getMessage();
        header('Location: ../views/login.php');
        exit;
    }
} elseif ($acao === 'logout') {
    $auth = new AuthController();
    $auth->logout();
} else {
    echo 'Acao invalida';
}
