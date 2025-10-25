<?php
require_once __DIR__ . '/includes/header.php';
if (!empty($_SESSION['user'])) { header('Location: /uts pemograman/index.php'); exit; }
?>
<section class="auth-card">
  <h1 class="title">Masuk</h1>
  <?php if (!empty($_GET['error'])): ?>
    <div class="alert error">
      <?= $_GET['error']==='csrf' ? 'Sesi kedaluwarsa. Coba lagi.' : 'Email atau kata sandi salah.' ?>
    </div>
  <?php endif; ?>
  <form action="/uts pemograman/actions/login.php" method="post" class="form" novalidate>
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrf_token()) ?>">
    <label>Email
      <input type="email" name="email" required autocomplete="email">
    </label>
    <label>Kata Sandi
      <input type="password" name="password" required autocomplete="current-password">
    </label>
    <button class="btn wfull" type="submit">Masuk</button>
    <p class="muted">Belum punya akun? <a href="/uts pemograman/register.php">Daftar</a></p>
  </form>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
