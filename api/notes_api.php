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
  case 'toggle_pin': {
    $id = intval($_POST['id'] ?? 0);
    $stmt = $mysqli->prepare('UPDATE notes SET is_pinned = IF(is_pinned=1,0,1), updated_at=NOW() WHERE id=? AND user_id=?');
    $stmt->bind_param('ii', $id, $user_id);
    $ok = $stmt->execute();
    echo json_encode(['ok'=>$ok]);
    break;
  }
  case 'reorder': {
    // expects JSON: [{id, sort_order}, ...]
    $payload = json_decode($_POST['items'] ?? '[]', true);
    if (!is_array($payload)) { echo json_encode(['ok'=>false]); break; }
    $stmt = $mysqli->prepare('UPDATE notes SET sort_order=? WHERE id=? AND user_id=?');
    foreach ($payload as $it) {
      $order = intval($it['sort_order'] ?? 0);
      $id = intval($it['id'] ?? 0);
      $stmt->bind_param('iii', $order, $id, $user_id);
      $stmt->execute();
    }
    echo json_encode(['ok'=>true]);
    break;
  }
  default: {
    echo json_encode(['ok'=>false, 'error'=>'unknown_action']);
  }
}
