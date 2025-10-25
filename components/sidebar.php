<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/auth.php';
if (!current_user_id()) { return; }
$user_id = current_user_id();

$ws = $mysqli->prepare('SELECT id, name FROM workspaces WHERE user_id = ? ORDER BY name');
$ws->bind_param('i', $user_id);
$ws->execute();
$workspaces = $ws->get_result()->fetch_all(MYSQLI_ASSOC);

$tg = $mysqli->prepare('SELECT id, name FROM tags WHERE user_id = ? ORDER BY name');
$tg->bind_param('i', $user_id);
$tg->execute();
$tags = $tg->get_result()->fetch_all(MYSQLI_ASSOC);
?>
<aside id="sidebar" class="sidebar" role="complementary" aria-label="Sidebar Navigation">
  <nav>
    <a href="/uts pemograman/index.php">🏠 Dashboard</a>
    <a href="/uts pemograman/pages/favorites.php">⭐ Favorit</a>
    <a href="/uts pemograman/pages/trash.php">🗑️ Sampah</a>
    <a href="/uts pemograman/pages/analytics.php">📊 Analytics</a>
    <a href="/uts pemograman/pages/settings.php">⚙️ Settings</a>
    <a href="/uts pemograman/pages/help.php">❓ Bantuan</a>
  </nav>
  <div class="sidebar-section">
    <div class="sidebar-title">Workspace</div>
    <div class="sidebar-list">
      <?php foreach ($workspaces as $w): ?>
        <a href="/uts pemograman/pages/workspace.php?id=<?= $w['id'] ?>"># <?= htmlspecialchars($w['name']) ?></a>
      <?php endforeach; ?>
      <form class="inline" action="/uts pemograman/api/user_api.php" method="post" onsubmit="return addWorkspace(event)">
        <input type="text" name="name" placeholder="Tambah workspace">
        <input type="hidden" name="action" value="create_workspace">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
      </form>
    </div>
  </div>
  <div class="sidebar-section">
    <div class="sidebar-title">Tag</div>
    <div class="sidebar-list">
      <?php foreach ($tags as $t): ?>
        <a href="/uts pemograman/pages/workspace.php?tag=<?= $t['id'] ?>">#<?= htmlspecialchars($t['name']) ?></a>
      <?php endforeach; ?>
      <form class="inline" action="/uts pemograman/api/user_api.php" method="post" onsubmit="return addTag(event)">
        <input type="text" name="name" placeholder="Tambah tag">
        <input type="hidden" name="action" value="create_tag">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
      </form>
    </div>
  </div>
</aside>
<script>
async function addWorkspace(e){ e.preventDefault(); const f = e.target; const name=f.name.value.trim(); if(!name) return false; const fd=new URLSearchParams(); fd.set('action','create_workspace'); fd.set('name',name); fd.set('csrf_token',document.querySelector('meta[name="csrf-token"]').content); await fetch('/uts pemograman/api/user_api.php',{method:'POST',body:fd}); location.reload(); return false; }
async function addTag(e){ e.preventDefault(); const f = e.target; const name=f.name.value.trim(); if(!name) return false; const fd=new URLSearchParams(); fd.set('action','create_tag'); fd.set('name',name); fd.set('csrf_token',document.querySelector('meta[name="csrf-token"]').content); await fetch('/uts pemograman/api/user_api.php',{method:'POST',body:fd}); location.reload(); return false; }
</script>
