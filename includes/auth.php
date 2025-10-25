<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function require_login() {
    if (empty($_SESSION['user'])) {
        header('Location: /uts pemograman/login.php');
        exit;
    }
}

function current_user_id() {
    return $_SESSION['user']['id'] ?? null;
}
?>
