<?php
require_once __DIR__ . '/../includes/auth.php';
require_login();
require_once __DIR__ . '/../includes/csrf.php';
verify_csrf();
require_once __DIR__ . '/../config/db.php';

$user_id = current_user_id();
$id = intval($_POST['id'] ?? 0);
$title = trim($_POST['title'] ?? '');
$content = $_POST['content'] ?? '';
$is_favorite = isset($_POST['is_favorite']) ? (int)$_POST['is_favorite'] : 0;

if ($id > 0) {
  $stmt = $mysqli->prepare('UPDATE notes SET title=?, content=?, is_favorite=?, updated_at=NOW() WHERE id=? AND user_id=?');
  $stmt->bind_param('ssiii', $title, $content, $is_favorite, $id, $user_id);
  $ok = $stmt->execute();
  header('Content-Type: application/json');
  echo json_encode(['ok'=>$ok, 'id'=>$id]);
  exit;
} else {
  $stmt = $mysqli->prepare('INSERT INTO notes (user_id, title, content, is_favorite, created_at, updated_at) VALUES (?, ?, ?, ?, NOW(), NOW())');
  $stmt->bind_param('issi', $user_id, $title, $content, $is_favorite);
  $ok = $stmt->execute();
  header('Content-Type: application/json');
  echo json_encode(['ok'=>$ok, 'id'=>$stmt->insert_id]);
  exit;
}
