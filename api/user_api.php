<?php
require_once __DIR__ . '/../includes/auth.php';
require_login();
require_once __DIR__ . '/../includes/csrf.php';
require_once __DIR__ . '/../config/db.php';
header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];
$action = $_POST['action'] ?? $_GET['action'] ?? '';
$user_id = current_user_id();

if ($method === 'POST') { verify_csrf(); }

switch ($action) {
  case 'create_workspace': {
    $name = trim($_POST['name'] ?? '');
    if ($name === '') { echo json_encode(['ok'=>false]); break; }
    $stmt = $mysqli->prepare('INSERT INTO workspaces (user_id, name) VALUES (?, ?)');
    $stmt->bind_param('is', $user_id, $name);
    $ok = $stmt->execute();
    echo json_encode(['ok'=>$ok, 'id'=>$stmt->insert_id]);
    break;
  }
  case 'create_tag': {
    $name = trim($_POST['name'] ?? '');
    if ($name === '') { echo json_encode(['ok'=>false]); break; }
    $stmt = $mysqli->prepare('INSERT INTO tags (user_id, name) VALUES (?, ?)');
    $stmt->bind_param('is', $user_id, $name);
    $ok = $stmt->execute();
    echo json_encode(['ok'=>$ok, 'id'=>$stmt->insert_id]);
    break;
  }
  case 'assign_tag': {
    $note_id = intval($_POST['note_id'] ?? 0);
    $tag_id = intval($_POST['tag_id'] ?? 0);
    if ($note_id<=0 || $tag_id<=0) { echo json_encode(['ok'=>false]); break; }
    // Ensure ownership
    $chk = $mysqli->prepare('SELECT id FROM notes WHERE id=? AND user_id=?');
    $chk->bind_param('ii', $note_id, $user_id);
    $chk->execute();
    if (!$chk->get_result()->fetch_assoc()) { echo json_encode(['ok'=>false]); break; }
    $stmt = $mysqli->prepare('INSERT IGNORE INTO note_tags (note_id, tag_id) VALUES (?, ?)');
    $stmt->bind_param('ii', $note_id, $tag_id);
    $ok = $stmt->execute();
    echo json_encode(['ok'=>$ok]);
    break;
  }
  default: {
    echo json_encode(['ok'=>false, 'error'=>'unknown_action']);
  }
}
