<?php
require_once __DIR__ . '/../includes/auth.php';
require_login();
require_once __DIR__ . '/../config/db.php';
header('Content-Type: application/json');

$user_id = current_user_id();
$type = $_GET['type'] ?? 'per_month';

switch ($type) {
  case 'per_month': {
    $sql = "SELECT DATE_FORMAT(created_at, '%Y-%m') AS ym, COUNT(*) AS cnt FROM notes WHERE user_id=? GROUP BY ym ORDER BY ym ASC";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    echo json_encode($rows);
    break;
  }
  case 'daily_activity': {
    $sql = "SELECT DATE(updated_at) AS d, COUNT(*) AS cnt FROM notes WHERE user_id=? AND updated_at >= DATE_SUB(CURDATE(), INTERVAL 30 DAY) GROUP BY d ORDER BY d ASC";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    echo json_encode($rows);
    break;
  }
  case 'fav_vs_normal': {
    $sql = "SELECT is_favorite AS fav, COUNT(*) AS cnt FROM notes WHERE user_id=? GROUP BY is_favorite";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    echo json_encode($rows);
    break;
  }
  default: echo json_encode([]);
}
