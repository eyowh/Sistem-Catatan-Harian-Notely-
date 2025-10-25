<?php
require_once __DIR__ . '/../includes/auth.php';
require_login();
require_once __DIR__ . '/../includes/csrf.php';
verify_csrf();
require_once __DIR__ . '/../config/db.php';

$user_id = current_user_id();
$id = intval($_POST['id'] ?? 0);
$stmt = $mysqli->prepare('DELETE FROM notes WHERE id=? AND user_id=?');
$stmt->bind_param('ii', $id, $user_id);
$stmt->execute();
header('Location: /uts pemograman/index.php?filter=trash');
