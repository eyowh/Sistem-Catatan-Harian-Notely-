<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function require_login() {
    if (empty($_SESSION['user'])) {
        require_once __DIR__ . '/base.php';
        $BASE = app_base();
        header('Location: ' . $BASE . '/login.php');
        exit;
    }
}

function current_user_id() {
    return $_SESSION['user']['id'] ?? null;
}
?>
