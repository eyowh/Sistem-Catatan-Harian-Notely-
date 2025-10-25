<?php
require_once __DIR__ . '/../includes/auth.php';
require_login();
require_once __DIR__ . '/../includes/csrf.php';
verify_csrf();
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/base.php';
$BASE = app_base();

$user_id = current_user_id();
$id = intval($_POST['id'] ?? 0);
if ($id <= 0) { header('Location: ' . $BASE . '/note.php'); exit; }

if (!empty($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
  $uploadDir = __DIR__ . '/../assets/uploads/';
  if (!is_dir($uploadDir)) { mkdir($uploadDir, 0775, true); }
  $allowed = ['jpg','jpeg','png','gif'];
  $ext = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));
  if (!in_array($ext, $allowed, true)) { header('Location: ' . $BASE . '/note.php?id=' . $id); exit; }
  if (($_FILES['file']['size'] ?? 0) > 2*1024*1024) { header('Location: ' . $BASE . '/note.php?id=' . $id); exit; }
  $newName = 'att_' . $user_id . '_' . $id . '_' . time() . '.' . $ext;
  $dest = $uploadDir . $newName;
  if (move_uploaded_file($_FILES['file']['tmp_name'], $dest)) {
    $publicPath = $BASE . '/assets/uploads/' . $newName;
    $stmt = $mysqli->prepare('UPDATE notes SET attachment_path=?, updated_at=NOW() WHERE id=? AND user_id=?');
    $stmt->bind_param('sii', $publicPath, $id, $user_id);
    $stmt->execute();
  }
}
header('Location: ' . $BASE . '/note.php?id=' . $id);
