<?php
require_once __DIR__ . '/session_safe.php';
if (isset($_SESSION['user_id'])) {
    header('Location: views/dashboard.php');
    exit;
} else {
    header('Location: views/login.php');
    exit;
}
