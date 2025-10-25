<?php
require_once __DIR__ . '/../config/db.php';
session_start();
$BASE = (function(){ require_once __DIR__ . '/../includes/base.php'; return app_base(); })();
$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$pass = $_POST['password'] ?? '';

if ($name === '' || $email === '' || $pass === '') { header('Location: ' . $BASE . '/register.php'); exit; }

$hash = password_hash($pass, PASSWORD_DEFAULT);
$stmt = $mysqli->prepare('INSERT INTO users (name, email, password) VALUES (?, ?, ?)');
$stmt->bind_param('sss', $name, $email, $hash);
if ($stmt->execute()) {
  $_SESSION['user'] = ['id'=>$stmt->insert_id, 'name'=>$name, 'email'=>$email];
  header('Location: ' . $BASE . '/index.php');
  exit;
}
header('Location: ' . $BASE . '/register.php');
