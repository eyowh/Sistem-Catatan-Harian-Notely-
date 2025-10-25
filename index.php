<?php
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/csrf.php';
require_once __DIR__ . '/includes/base.php';
$BASE = app_base();

// If user not logged in, show landing page and stop.
if (empty($_SESSION['user'])): ?>
  <section class="landing-hero fade-in">
    <div class="hero-text">
      <h1 class="hero-title">Catat ide. Fokus pada yang penting.</h1>
      <p class="hero-sub">Notely adalah tempat sederhana untuk menulis, mengorganisir, dan menemukan catatan Anda cepat, aman, dan nyaman di semua perangkat.</p>
      <div class="hero-cta">
        <a class="btn" href="<?= htmlspecialchars($BASE) ?>/register.php">Mulai Gratis</a>
        <a class="btn ghost" href="<?= htmlspecialchars($BASE) ?>/login.php">Masuk</a>
      </div>
    </div>
    <div class="hero-illus">
      <div class="illus-card">
        <img src="<?= htmlspecialchars($BASE) ?>/assets/uploads/landing-hero.png" alt="Ilustrasi Notely">
      </div>
    </div>
  </section>

  <section class="features-grid">
    <article class="feature-card">
      <h3>Ringkas & Rapi</h3>
      <p>Tampilan kartu bergaya Notion dengan ringkasan 3 baris dan bullet list yang jelas.</p>
    </article>
    <article class="feature-card">
      <h3>Cepat Dicari</h3>
      <p>Filter favorit, sampah, workspace dan tag untuk temukan catatan dalam hitungan detik.</p>
    </article>
    <article class="feature-card">
      <h3>Aman</h3>
      <p>Proteksi CSRF untuk semua aksi serta batasan upload agar data tetap terjaga.</p>
    </article>
  </section>
<?php require_once __DIR__ . '/includes/footer.php'; exit; endif;

require_once __DIR__ . '/config/db.php';

$user_id = current_user_id();
$q = trim($_GET['q'] ?? '');
$filter = $_GET['filter'] ?? 'all'; // all|favorites|trash
$view = $_GET['view'] ?? ($_COOKIE['viewMode'] ?? 'list');

$sql = "SELECT id, title, content, is_favorite, is_trashed, updated_at FROM notes WHERE user_id = ?";
$params = [$user_id];
$types = 'i';

if ($filter === 'favorites') { $sql .= " AND is_favorite = 1"; }
if ($filter === 'trash') { $sql .= " AND is_trashed = 1"; } else { $sql .= " AND is_trashed = 0"; }

if ($q !== '') {
  $sql .= " AND (title LIKE ? OR content LIKE ?)";
  $params[] = "%$q%";
  $params[] = "%$q%";
  $types .= 'ss';
}

$sql .= " ORDER BY updated_at DESC";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();
$notes = $result->fetch_all(MYSQLI_ASSOC);
?>
<section class="toolbar">
  <div class="filters">
    <a class="chip <?= $filter==='all'?'active':'' ?>" href="?filter=all">Semua</a>
    <a class="chip <?= $filter==='favorites'?'active':'' ?>" href="?filter=favorites">Favorit</a>
    <a class="chip <?= $filter==='trash'?'active':'' ?>" href="?filter=trash">Sampah</a>
  </div>
</section>

<section class="notes <?= $view==='grid'?'grid':'' ?>" id="notes">
  <?php if (empty($notes)): ?>
    <div class="empty">
      Belum ada catatan.
      <div style="margin-top:12px">
        <a class="btn" href="<?= htmlspecialchars($BASE) ?>/note.php">+ Tambah Catatan Baru</a>
      </div>
    </div>
  <?php endif; ?>
  <?php foreach ($notes as $n): ?>
    <?php
      $raw = $n['content'] ?? '';
      // Normalize block separators to newlines for text detection
      $norm = preg_replace(['/(<br\s*\/?>)/i','/<\/(p|div)>/i'], "\n", $raw);
      $plain = trim(strip_tags($norm));
      $text = trim(preg_replace('/\s+/', ' ', $plain));
      $lines = preg_split('/\r?\n/', $plain);
      $first = isset($lines[0]) ? trim($lines[0]) : '';
      $hasHtmlList = preg_match('/<\s*(ul|ol|li)\b/i', $raw) === 1;
      $isTextBullet = preg_match('/^(?:[-*•]|\[ \]|\[x\])/i', $first) === 1;
      $isList = $hasHtmlList || $isTextBullet;
      $listItems = [];
      if ($hasHtmlList) {
        // Extract first few <li> items safely
        if (preg_match_all('/<\s*li[^>]*>(.*?)<\s*\/\s*li>/is', $raw, $m)) {
          foreach ($m[1] as $li) {
            $liText = trim(strip_tags($li));
            if ($liText !== '') { $listItems[] = $liText; }
            if (count($listItems) >= 5) break;
          }
        }
      } elseif ($isTextBullet) {
        foreach ($lines as $ln) {
          $ln = trim($ln);
          if ($ln === '') continue;
          if (!preg_match('/^(?:[-*•]|\[ \]|\[x\])/i', $ln)) break;
          $ln = preg_replace('/^(?:[-*•]|\[ \]|\[x\])\s*/i', '', $ln);
          $listItems[] = $ln;
          if (count($listItems) >= 5) break;
        }
      }
    ?>
    <article class="note-card <?= $n['is_favorite'] ? 'fav' : '' ?> fade-in">
      <a class="note-link" href="<?= htmlspecialchars($BASE) ?>/note.php?id=<?= $n['id'] ?>">
        <h3 class="note-title"><?= htmlspecialchars($n['title'] ?: 'Tanpa Judul') ?></h3>
        <div class="note-summary <?= $isList ? 'list' : 'p' ?>">
          <?php if ($isList && !empty($listItems)): ?>
            <ul>
              <?php foreach ($listItems as $it): ?>
                <li><?= htmlspecialchars($it) ?></li>
              <?php endforeach; ?>
            </ul>
          <?php else: ?>
            <p class="note-excerpt note-summary p"><?= htmlspecialchars(mb_strimwidth($text, 0, 300, '…')) ?></p>
          <?php endif; ?>
        </div>
        <div class="note-meta">
          <span><?= date('d M Y', strtotime($n['updated_at'])) ?></span>
        </div>
      </a>
      <button class="icon-btn toggle-fav fav-icon" data-id="<?= $n['id'] ?>" title="Favorit" aria-label="Favorit">
        <i data-feather="star"></i>
      </button>
      <div class="note-actions">
        <a class="icon-btn" href="<?= htmlspecialchars($BASE) ?>/note.php?id=<?= $n['id'] ?>" title="Edit" aria-label="Edit"><i data-feather="edit-2"></i></a>
        <?php if ($filter==='trash'): ?>
          <form method="post" action="<?= htmlspecialchars($BASE) ?>/actions/restore_note.php">
            <input type="hidden" name="id" value="<?= $n['id'] ?>">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrf_token()) ?>">
            <button class="icon-btn" title="Pulihkan" aria-label="Pulihkan"><i data-feather="rotate-ccw"></i></button>
          </form>
          <form method="post" action="<?= htmlspecialchars($BASE) ?>/actions/hard_delete_note.php" onsubmit="return confirm('Hapus permanen catatan ini?')">
            <input type="hidden" name="id" value="<?= $n['id'] ?>">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrf_token()) ?>">
            <button class="icon-btn" title="Hapus Permanen" aria-label="Hapus Permanen"><i data-feather="trash-2"></i></button>
          </form>
        <?php else: ?>
          <form method="post" action="<?= htmlspecialchars($BASE) ?>/actions/delete_note.php" onsubmit="return confirm('Pindahkan ke sampah?')">
            <input type="hidden" name="id" value="<?= $n['id'] ?>">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrf_token()) ?>">
            <button class="icon-btn" title="Hapus" aria-label="Hapus"><i data-feather="trash-2"></i></button>
          </form>
        <?php endif; ?>
      </div>
    </article>
  <?php endforeach; ?>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
