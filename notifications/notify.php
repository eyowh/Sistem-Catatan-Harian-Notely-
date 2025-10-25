<?php
// Simple reminder notifier stub: mark due reminders as sent and output them
require_once __DIR__ . '/../includes/auth.php';
require_login();
require_once __DIR__ . '/../config/db.php';
header('Content-Type: application/json');
$user_id = current_user_id();
$stmt = $mysqli->prepare('SELECT id, note_id, message FROM reminders WHERE user_id=? AND sent=0 AND remind_at <= NOW()');
$stmt->bind_param('i', $user_id);
$stmt->execute();
$rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
if (!empty($rows)) {
  $upd = $mysqli->prepare('UPDATE reminders SET sent=1 WHERE id=? AND user_id=?');
  foreach ($rows as $r) { $id=$r['id']; $upd->bind_param('ii', $id, $user_id); $upd->execute(); }
}
echo json_encode(['items'=>$rows]);
