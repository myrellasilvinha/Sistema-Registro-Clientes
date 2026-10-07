<?php
require_once __DIR__ . '/session_safe.php';

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: ../views/login.php');
        exit;
    }
}

function logoutUser() {
    session_destroy();
    header('Location: ../views/login.php');
    exit;
}
