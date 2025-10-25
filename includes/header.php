<?php if (session_status() === PHP_SESSION_NONE) { session_start(); } ?>
<!doctype html>
<html lang="id" data-theme="light">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Notely</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <?php require_once __DIR__ . '/base.php'; $BASE = app_base(); ?>
  <link rel="stylesheet" href="<?= htmlspecialchars($BASE) ?>/assets/css/style.css">
  <?php require_once __DIR__ . '/csrf.php'; ?>
  <meta name="csrf-token" content="<?= htmlspecialchars(csrf_token()) ?>">
  <meta name="app-base" content="<?= htmlspecialchars($BASE) ?>">
  <script>window.APP_BASE='<?= htmlspecialchars($BASE) ?>';</script>
  </head>
<body class="<?= !empty($_SESSION['user']) ? 'logged-in' : 'guest' ?> <?= isset($PAGE_CLASS) ? htmlspecialchars($PAGE_CLASS) : '' ?>">
<header class="topbar">
  <div class="brand">
    <button id="sidebarToggle" class="icon-btn only-mobile" title="Menu" aria-label="Menu">☰</button>
    <span>🗒️ Notely</span>
  </div>
  <div class="top-actions">
    <?php if (!empty($_SESSION['user'])): ?>
      <a class="btn" href="<?= htmlspecialchars($BASE) ?>/note.php">+ Catatan</a>
      <form class="search" action="<?= htmlspecialchars($BASE) ?>/index.php" method="get">
        <input type="text" name="q" placeholder="Cari catatan..." value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
      </form>
      <button id="toggleView" class="icon-btn" title="Ganti tampilan">🔳</button>
      <button id="toggleTheme" class="icon-btn" title="Tema">🌓</button>
      <div class="user-menu">
        <span class="user-name"><?= htmlspecialchars($_SESSION['user']['name'] ?? '') ?></span>
        <a class="link" href="<?= htmlspecialchars($BASE) ?>/actions/logout.php">Keluar</a>
      </div>
    <?php else: ?>
      <a class="btn" href="<?= htmlspecialchars($BASE) ?>/login.php">Masuk</a>
      <a class="btn ghost" href="<?= htmlspecialchars($BASE) ?>/register.php">Daftar</a>
    <?php endif; ?>
  </div>
  <script src="https://unpkg.com/feather-icons"></script>
</header>
<div class="layout <?= !empty($_SESSION['user']) ? 'has-sidebar' : '' ?>">
  <?php if (!empty($_SESSION['user'])) { require_once __DIR__ . '/../components/sidebar.php'; } ?>
  <main class="container">
