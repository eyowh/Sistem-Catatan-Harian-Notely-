<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/csrf.php';
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once __DIR__ . '/../includes/base.php';
$BASE = app_base();

// CSRF check (uses verify_csrf from includes/csrf.php)
verify_csrf();

$email = strtolower(trim($_POST['email'] ?? ''));
$pass = $_POST['password'] ?? '';

$stmt = $mysqli->prepare('SELECT id, name, email, password FROM users WHERE email = ? LIMIT 1');
$stmt->bind_param('s', $email);
$stmt->execute();
$res = $stmt->get_result();
$user = $res->fetch_assoc();

if ($user && password_verify($pass, $user['password'])) {
    $_SESSION['user'] = ['id'=>$user['id'], 'name'=>$user['name'], 'email'=>$user['email']];
    header('Location: ' . $BASE . '/index.php');
    exit;
}

header('Location: ' . $BASE . '/login.php?error=1');
