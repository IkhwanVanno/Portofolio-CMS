<?php
require_once '../includes/config.php';
requireLogin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Panel — <?= $pageTitle ?? 'Dashboard' ?></title>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    :root {
      --blue: #1B2B4B; --blue-mid: #2A3F6B; --blue-light: #3D5A8A;
      --gold: #C9A96E; --gold-light: #E8C99A; --cream: #F5F0E8;
      --sidebar-w: 240px; --header-h: 64px;
      --font-display: 'Cormorant Garamond', serif; --font-body: 'DM Sans', sans-serif;
      --danger: #e05c6d; --success: #4caf7d; --warning: #f0a04b;
    }
    body { font-family: var(--font-body); background: #0f1826; color: #c8d0e0; display: flex; min-height: 100vh; }

    /* Sidebar */
    .sidebar {
      width: var(--sidebar-w); background: var(--blue); position: fixed;
      top: 0; left: 0; bottom: 0; z-index: 100; overflow-y: auto;
      border-right: 1px solid rgba(201,169,110,0.12);
      display: flex; flex-direction: column;
    }
    .sidebar-brand {
      padding: 1.5rem 1.5rem 1rem; font-family: var(--font-display);
      font-size: 1.4rem; font-weight: 300; color: var(--gold-light);
      border-bottom: 1px solid rgba(201,169,110,0.12); letter-spacing: 0.04em;
    }
    .sidebar-brand span { display: block; font-family: var(--font-body); font-size: 0.7rem; color: rgba(201,169,110,0.5); letter-spacing: 0.15em; text-transform: uppercase; margin-top: 0.2rem; }
    .sidebar-nav { padding: 1rem 0; flex: 1; }
    .nav-section { padding: 0.75rem 1.5rem 0.35rem; font-size: 0.65rem; letter-spacing: 0.2em; text-transform: uppercase; color: rgba(201,169,110,0.4); font-weight: 500; }
    .nav-item a {
      display: flex; align-items: center; gap: 0.75rem; padding: 0.65rem 1.5rem;
      color: rgba(200,208,224,0.7); text-decoration: none; font-size: 0.875rem;
      transition: all 0.2s; border-left: 2px solid transparent;
    }
    .nav-item a:hover, .nav-item a.active {
      color: var(--gold-light); background: rgba(201,169,110,0.08);
      border-left-color: var(--gold);
    }
    .nav-item a .icon { width: 18px; text-align: center; font-size: 1rem; }
    .sidebar-footer {
      padding: 1rem 1.5rem; border-top: 1px solid rgba(201,169,110,0.12);
      font-size: 0.8rem; color: rgba(200,208,224,0.5);
    }
    .sidebar-footer strong { color: var(--gold-light); font-weight: 400; }
    .sidebar-footer a { color: rgba(220,80,80,0.7); text-decoration: none; font-size: 0.75rem; display: block; margin-top: 0.4rem; }
    .sidebar-footer a:hover { color: #e05c6d; }

    /* Main */
    .main-content { margin-left: var(--sidebar-w); flex: 1; min-height: 100vh; }
    .topbar {
      height: var(--header-h); background: #141f30; border-bottom: 1px solid rgba(255,255,255,0.06);
      display: flex; align-items: center; padding: 0 2rem; position: sticky; top: 0; z-index: 50;
    }
    .topbar h1 { font-family: var(--font-display); font-size: 1.5rem; font-weight: 300; color: #e0e8f4; }
    .topbar-right { margin-left: auto; display: flex; gap: 1rem; align-items: center; }
    .btn-view { padding: 0.45rem 1rem; background: rgba(201,169,110,0.15); border: 1px solid rgba(201,169,110,0.3); border-radius: 2px; color: var(--gold-light); text-decoration: none; font-size: 0.8rem; transition: all 0.2s; }
    .btn-view:hover { background: rgba(201,169,110,0.25); }

    .page-body { padding: 2rem; }

    /* Flash */
    .flash {
      padding: 0.85rem 1.25rem; border-radius: 2px; margin-bottom: 1.5rem; font-size: 0.875rem;
      border: 1px solid;
    }
    .flash.success { background: rgba(76,175,125,0.1); border-color: rgba(76,175,125,0.3); color: #6fcfa0; }
    .flash.error { background: rgba(224,92,109,0.1); border-color: rgba(224,92,109,0.3); color: #f09090; }

    /* Cards */
    .card {
      background: #141f30; border: 1px solid rgba(255,255,255,0.07); border-radius: 3px;
      overflow: hidden; margin-bottom: 1.5rem;
    }
    .card-header {
      padding: 1.25rem 1.5rem; border-bottom: 1px solid rgba(255,255,255,0.06);
      display: flex; align-items: center; justify-content: space-between;
    }
    .card-header h2 { font-size: 0.95rem; font-weight: 500; color: #e0e8f4; }
    .card-body { padding: 1.5rem; }

    /* Forms */
    .form-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.25rem; }
    .form-group { display: flex; flex-direction: column; gap: 0.4rem; }
    .form-group.full { grid-column: 1/-1; }
    label { font-size: 0.75rem; letter-spacing: 0.08em; text-transform: uppercase; color: rgba(200,208,224,0.5); font-weight: 500; }
    input[type=text], input[type=email], input[type=url], input[type=password], textarea, select {
      background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1);
      border-radius: 2px; padding: 0.7rem 0.9rem; color: #c8d0e0;
      font-family: var(--font-body); font-size: 0.9rem; width: 100%;
      transition: border-color 0.2s, background 0.2s;
    }
    input:focus, textarea:focus, select:focus { outline: none; border-color: rgba(201,169,110,0.5); background: rgba(255,255,255,0.07); }
    textarea { resize: vertical; min-height: 100px; }
    .current-img { margin-top: 0.5rem; }
    .current-img img { height: 80px; border-radius: 2px; border: 1px solid rgba(255,255,255,0.1); object-fit: cover; }

    /* Buttons */
    .btn { display: inline-flex; align-items: center; gap: 0.4rem; padding: 0.6rem 1.2rem; border-radius: 2px; border: none; cursor: pointer; font-family: var(--font-body); font-size: 0.85rem; font-weight: 500; text-decoration: none; transition: all 0.2s; }
    .btn-primary { background: var(--gold); color: var(--blue); }
    .btn-primary:hover { background: var(--gold-light); }
    .btn-danger { background: rgba(224,92,109,0.15); color: #f09090; border: 1px solid rgba(224,92,109,0.3); }
    .btn-danger:hover { background: rgba(224,92,109,0.25); }
    .btn-sm { padding: 0.4rem 0.8rem; font-size: 0.8rem; }
    .btn-secondary { background: rgba(255,255,255,0.07); color: #c8d0e0; border: 1px solid rgba(255,255,255,0.12); }
    .btn-secondary:hover { background: rgba(255,255,255,0.12); }

    /* Table */
    table { width: 100%; border-collapse: collapse; }
    thead th { text-align: left; padding: 0.75rem 1rem; font-size: 0.72rem; letter-spacing: 0.1em; text-transform: uppercase; color: rgba(200,208,224,0.4); border-bottom: 1px solid rgba(255,255,255,0.06); font-weight: 500; }
    tbody td { padding: 0.9rem 1rem; border-bottom: 1px solid rgba(255,255,255,0.04); vertical-align: middle; font-size: 0.875rem; }
    tbody tr:hover { background: rgba(255,255,255,0.02); }
    tbody tr:last-child td { border-bottom: none; }
    .td-img img { width: 60px; height: 45px; object-fit: cover; border-radius: 1px; border: 1px solid rgba(255,255,255,0.1); }
    .actions { display: flex; gap: 0.5rem; }

    /* Stats */
    .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 1rem; margin-bottom: 2rem; }
    .stat-card { background: #141f30; border: 1px solid rgba(255,255,255,0.07); border-radius: 3px; padding: 1.25rem 1.5rem; }
    .stat-num { font-family: var(--font-display); font-size: 2.5rem; font-weight: 300; color: var(--gold-light); line-height: 1; }
    .stat-label { font-size: 0.75rem; color: rgba(200,208,224,0.4); text-transform: uppercase; letter-spacing: 0.1em; margin-top: 0.35rem; }

    .form-actions { margin-top: 1.5rem; display: flex; gap: 0.75rem; }
    .text-muted { color: rgba(200,208,224,0.35); font-size: 0.8rem; }
    .badge { padding: 0.2rem 0.6rem; border-radius: 20px; font-size: 0.72rem; font-weight: 500; }
    .badge-gold { background: rgba(201,169,110,0.15); color: var(--gold-light); border: 1px solid rgba(201,169,110,0.3); }
  </style>
</head>
<body>

<aside class="sidebar">
  <div class="sidebar-brand">
    Admin Panel
    <span>Portfolio CMS</span>
  </div>
  <nav class="sidebar-nav">
    <div class="nav-section">Utama</div>
    <div class="nav-item"><a href="index.php" class="<?= basename($_SERVER['PHP_SELF']) === 'index.php' ? 'active' : '' ?>"><span class="icon">⊞</span> Dashboard</a></div>
    
    <div class="nav-section">Konten</div>
    <div class="nav-item"><a href="site-setup.php" class="<?= basename($_SERVER['PHP_SELF']) === 'site-setup.php' ? 'active' : '' ?>"><span class="icon">⚙</span> Site Setup</a></div>
    <div class="nav-item"><a href="about-me.php" class="<?= basename($_SERVER['PHP_SELF']) === 'about-me.php' ? 'active' : '' ?>"><span class="icon">👤</span> About Me</a></div>
    
    <div class="nav-section">Portfolio</div>
    <div class="nav-item"><a href="pengalaman.php" class="<?= basename($_SERVER['PHP_SELF']) === 'pengalaman.php' ? 'active' : '' ?>"><span class="icon">📋</span> Pengalaman</a></div>
    <div class="nav-item"><a href="perkerjaan.php" class="<?= basename($_SERVER['PHP_SELF']) === 'perkerjaan.php' ? 'active' : '' ?>"><span class="icon">💼</span> Perkerjaan</a></div>
    <div class="nav-item"><a href="sertifikat.php" class="<?= basename($_SERVER['PHP_SELF']) === 'sertifikat.php' ? 'active' : '' ?>"><span class="icon">🏆</span> Sertifikat</a></div>
    <div class="nav-item"><a href="galery.php" class="<?= basename($_SERVER['PHP_SELF']) === 'galery.php' ? 'active' : '' ?>"><span class="icon">🖼</span> Galeri</a></div>
    
    <div class="nav-section">Akun</div>
    <div class="nav-item"><a href="users.php" class="<?= basename($_SERVER['PHP_SELF']) === 'users.php' ? 'active' : '' ?>"><span class="icon">👥</span> Users</a></div>
  </nav>
  <div class="sidebar-footer">
    Hi, <strong><?= htmlspecialchars($_SESSION['admin_name']) ?></strong>
    <a href="logout.php">Keluar →</a>
  </div>
</aside>

<div class="main-content">
  <div class="topbar">
    <h1><?= $pageTitle ?? 'Dashboard' ?></h1>
    <div class="topbar-right">
      <a href="../index.php" target="_blank" class="btn-view">↗ Lihat Portfolio</a>
    </div>
  </div>
  <div class="page-body">
<?php
$flash = getFlash();
if ($flash):
?>
<div class="flash <?= $flash['type'] ?>"><?= htmlspecialchars($flash['msg']) ?></div>
<?php endif; ?>
