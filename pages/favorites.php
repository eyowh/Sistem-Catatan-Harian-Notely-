<?php
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/auth.php';
require_login();
require_once __DIR__ . '/../config/db.php';

$user_id = current_user_id();
$stmt = $mysqli->prepare("SELECT id, title, content, is_favorite, updated_at FROM notes WHERE user_id=? AND is_trashed=0 AND is_favorite=1 ORDER BY updated_at DESC");
$stmt->bind_param('i', $user_id);
$stmt->execute();
$notes = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>
<section class="toolbar"><h2 class="title">Favorit</h2></section>
<section class="notes" id="notes">
  <?php if (empty($notes)): ?><div class="empty">Belum ada catatan favorit.</div><?php endif; ?>
  <?php foreach ($notes as $n): ?>
    <article class="note-card">
      <?php require_once __DIR__ . '/../includes/base.php'; $BASE = app_base(); ?>
      <a class="note-link" href="<?= htmlspecialchars($BASE) ?>/note.php?id=<?= $n['id'] ?>">
        <h3 class="note-title">⭐ <?= htmlspecialchars($n['title'] ?: 'Tanpa Judul') ?></h3>
        <p class="note-excerpt"><?= htmlspecialchars(mb_strimwidth(strip_tags($n['content']), 0, 140, '…')) ?></p>
        <div class="note-meta"><span><?= date('d M Y H:i', strtotime($n['updated_at'])) ?></span></div>
      </a>
    </article>
  <?php endforeach; ?>
</section>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
