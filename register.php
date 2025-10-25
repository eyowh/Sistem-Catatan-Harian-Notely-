<?php
require_once __DIR__ . '/includes/header.php';
if (!empty($_SESSION['user'])) { header('Location: /uts pemograman/index.php'); exit; }
?>
<section class="auth-card">
  <h1 class="title">Daftar</h1>
  <form action="/uts pemograman/actions/register.php" method="post" class="form">
    <label>Nama
      <input type="text" name="name" required>
    </label>
    <label>Email
      <input type="email" name="email" required>
    </label>
    <label>Kata Sandi
      <input type="password" name="password" required>
    </label>
    <button class="btn wfull" type="submit">Buat Akun</button>
    <p class="muted">Sudah punya akun? <a href="/uts pemograman/login.php">Masuk</a></p>
  </form>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
