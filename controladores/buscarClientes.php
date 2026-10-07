<?php
require_once __DIR__ . '/../includes/auth_check.php';

$msg = isset($_GET['msg']) ? '?msg=' . urlencode($_GET['msg']) : '';
header('Location: ../views/mostrarClientes.php' . $msg);
exit;
