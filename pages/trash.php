<?php
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/auth.php';
require_login();
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/csrf.php';

$user_id = current_user_id();
$stmt = $mysqli->prepare("SELECT id, title, content, is_favorite, updated_at FROM notes WHERE user_id=? AND is_trashed=1 ORDER BY updated_at DESC");
$stmt->bind_param('i', $user_id);
$stmt->execute();
$notes = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>
<section class="toolbar"><h2 class="title">Sampah</h2></section>
<section class="notes" id="notes">
  <?php if (empty($notes)): ?><div class="empty">Tidak ada catatan di sampah.</div><?php endif; ?>
  <?php foreach ($notes as $n): ?>
    <article class="note-card">
      <h3 class="note-title"><?= htmlspecialchars($n['title'] ?: 'Tanpa Judul') ?></h3>
      <p class="note-excerpt"><?= htmlspecialchars(mb_strimwidth(strip_tags($n['content']), 0, 120, '…')) ?></p>
      <div class="note-actions">
        <?php require_once __DIR__ . '/../includes/base.php'; $BASE = app_base(); ?>
        <form method="post" action="<?= htmlspecialchars($BASE) ?>/actions/restore_note.php">
          <input type="hidden" name="id" value="<?= $n['id'] ?>">
          <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrf_token()) ?>">
          <button class="icon-btn" title="Pulihkan">♻️</button>
        </form>
        <form method="post" action="<?= htmlspecialchars($BASE) ?>/actions/hard_delete_note.php" onsubmit="return confirm('Hapus permanen catatan ini?')">
          <input type="hidden" name="id" value="<?= $n['id'] ?>">
          <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrf_token()) ?>">
          <button class="icon-btn" title="Hapus Permanen">🗑️</button>
        </form>
      </div>
    </article>
  <?php endforeach; ?>
</section>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
