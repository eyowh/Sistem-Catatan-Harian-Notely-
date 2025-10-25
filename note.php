<?php
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/auth.php';
require_login();
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/base.php'; $BASE = app_base();

$user_id = current_user_id();
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$note = [
  'id' => 0,
  'title' => '',
  'content' => '',
  'is_favorite' => 0,
  'attachment_path' => null,
];

if ($id > 0) {
  $stmt = $mysqli->prepare('SELECT * FROM notes WHERE id = ? AND user_id = ? LIMIT 1');
  $stmt->bind_param('ii', $id, $user_id);
  $stmt->execute();
  $res = $stmt->get_result();
  $note = $res->fetch_assoc();
  if (!$note) { http_response_code(404); echo '<div class="empty">Catatan tidak ditemukan.</div>'; require_once __DIR__ . '/includes/footer.php'; exit; }
}
?>
<section class="editor">
  <input id="noteId" type="hidden" value="<?= (int)$note['id'] ?>">
  <div class="editor-toolbar">
    <button id="favBtn" class="icon-btn" title="Favorit"><?= $note['is_favorite'] ? '⭐' : '☆' ?></button>
    <div class="editor-format">
      <button class="icon-btn" data-cmd="bold" title="Bold"><b>B</b></button>
      <button class="icon-btn" data-cmd="italic" title="Italic"><i>I</i></button>
      <button class="icon-btn" data-cmd="underline" title="Underline"><u>U</u></button>
      <button class="icon-btn" data-cmd="insertUnorderedList" title="Bullet List">• List</button>
      <button class="icon-btn" data-cmd="formatBlock" data-value="h2" title="Heading">H2</button>
      <button class="icon-btn" id="addLink" title="Link">🔗</button>
    </div>
    <a class="btn ghost" href="<?= htmlspecialchars($BASE) ?>/index.php">Kembali</a>
  </div>
  <input id="title" class="title-input" placeholder="Judul" value="<?= htmlspecialchars($note['title']) ?>">
  <div id="content" class="content-editable" contenteditable="true" placeholder="Tulis catatan di sini..."><?= $note['content'] ?></div>

  <form class="upload" action="<?= htmlspecialchars($BASE) ?>/actions/upload.php" method="post" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?= (int)$note['id'] ?>">
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
    <label>Lampiran
      <input type="file" name="file" accept="image/png,image/jpeg,image/gif">
    </label>
    <button class="btn" type="submit">Unggah</button>
    <?php if (!empty($note['attachment_path'])): ?>
      <a class="link" target="_blank" href="<?= htmlspecialchars($note['attachment_path']) ?>">Lihat Lampiran</a>
    <?php endif; ?>
  </form>
  <p class="muted" id="saveStatus">Menyimpan otomatis…</p>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
