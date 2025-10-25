<?php
require_once __DIR__ . '/../config/db.php';
session_start();
$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$pass = $_POST['password'] ?? '';

if ($name === '' || $email === '' || $pass === '') { header('Location: /uts pemograman/register.php'); exit; }

$hash = password_hash($pass, PASSWORD_DEFAULT);
$stmt = $mysqli->prepare('INSERT INTO users (name, email, password) VALUES (?, ?, ?)');
$stmt->bind_param('sss', $name, $email, $hash);
if ($stmt->execute()) {
  $_SESSION['user'] = ['id'=>$stmt->insert_id, 'name'=>$name, 'email'=>$email];
  header('Location: /uts pemograman/index.php');
  exit;
}
header('Location: /uts pemograman/register.php');
