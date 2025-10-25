<?php
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/auth.php';
require_login();
?>
<section class="toolbar"><h2 class="title">Settings</h2></section>
<section class="auth-card">
  <h3>Profil</h3>
  <p>Perubahan profil dan kata sandi dapat ditambahkan melalui API di iterasi berikutnya.</p>
  <h3 style="margin-top:16px">Tema</h3>
  <div>
    <button class="btn" onclick="localStorage.setItem('theme','light');document.documentElement.setAttribute('data-theme','light')">Light</button>
    <button class="btn ghost" onclick="localStorage.setItem('theme','dark');document.documentElement.setAttribute('data-theme','dark')">Dark</button>
  </div>
  <h3 style="margin-top:16px">Ekspor</h3>
  <p>Ekspor semua catatan ke .zip akan ditambahkan. Sementara ini, gunakan copy manual.</p>
</section>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
