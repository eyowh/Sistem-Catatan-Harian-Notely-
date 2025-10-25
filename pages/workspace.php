<?php
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/auth.php';
require_login();
require_once __DIR__ . '/../config/db.php';

$user_id = current_user_id();
$ws_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$tag_id = isset($_GET['tag']) ? intval($_GET['tag']) : 0;
$title = 'Workspace';
$notes = [];

if ($ws_id > 0) {
  $w = $mysqli->prepare('SELECT name FROM workspaces WHERE id=? AND user_id=?');
  $w->bind_param('ii', $ws_id, $user_id);
  $w->execute();
  $wr = $w->get_result()->fetch_assoc();
  $title = $wr ? ('Workspace: ' . $wr['name']) : 'Workspace';
  $stmt = $mysqli->prepare("SELECT id, title, content, is_favorite, is_pinned, updated_at FROM notes WHERE user_id=? AND is_trashed=0 AND workspace_id=? ORDER BY is_pinned DESC, sort_order DESC, updated_at DESC");
  $stmt->bind_param('ii', $user_id, $ws_id);
  $stmt->execute();
  $notes = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
} elseif ($tag_id > 0) {
  $t = $mysqli->prepare('SELECT name FROM tags WHERE id=? AND user_id=?');
  $t->bind_param('ii', $tag_id, $user_id);
  $t->execute();
  $tr = $t->get_result()->fetch_assoc();
  $title = $tr ? ('Tag: #' . $tr['name']) : 'Tag';
  $sql = "SELECT n.id, n.title, n.content, n.is_favorite, n.is_pinned, n.updated_at FROM notes n JOIN note_tags nt ON n.id=nt.note_id WHERE n.user_id=? AND n.is_trashed=0 AND nt.tag_id=? ORDER BY n.is_pinned DESC, n.sort_order DESC, n.updated_at DESC";
  $stmt = $mysqli->prepare($sql);
  $stmt->bind_param('ii', $user_id, $tag_id);
  $stmt->execute();
  $notes = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}
?>
<section class="toolbar"><h2 class="title"><?= htmlspecialchars($title) ?></h2></section>
<section class="notes" id="notes">
  <?php if (empty($notes)): ?><div class="empty">Belum ada catatan.</div><?php endif; ?>
  <?php foreach ($notes as $n): ?>
    <article class="note-card">
      <?php require_once __DIR__ . '/../includes/base.php'; $BASE = app_base(); ?>
      <a class="note-link" href="<?= htmlspecialchars($BASE) ?>/note.php?id=<?= $n['id'] ?>">
        <h3 class="note-title"><?= $n['is_pinned'] ? '📌 ' : '' ?><?= htmlspecialchars($n['title'] ?: 'Tanpa Judul') ?></h3>
        <p class="note-excerpt"><?= htmlspecialchars(mb_strimwidth(strip_tags($n['content']), 0, 140, '…')) ?></p>
        <div class="note-meta"><span><?= date('d M Y H:i', strtotime($n['updated_at'])) ?></span></div>
      </a>
    </article>
  <?php endforeach; ?>
</section>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
