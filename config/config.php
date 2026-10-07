<?php
if (!defined('BASE_PATH')) {
    define('BASE_PATH', dirname(__DIR__));
}

// Funciona tanto em /cadastro-clientes quanto em outro nome de pasta.
if (!defined('APP_URL')) {
    $base = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '')), '/');
    define('APP_URL', $base === '/' ? '' : $base);
}
