<?php
$pageTitle = 'Dashboard';
require_once 'header.php';

$db = getDB();
$counts = [
    'pengalaman' => $db->query("SELECT COUNT(*) FROM pengalaman")->fetchColumn(),
    'perkerjaan' => $db->query("SELECT COUNT(*) FROM perkerjaan")->fetchColumn(),
    'sertifikat' => $db->query("SELECT COUNT(*) FROM sertifikat")->fetchColumn(),
    'galery'     => $db->query("SELECT COUNT(*) FROM galery")->fetchColumn(),
];
?>
<div class="stats-grid">
  <div class="stat-card">
    <div class="stat-num"><?= $counts['pengalaman'] ?></div>
    <div class="stat-label">Pengalaman</div>
  </div>
  <div class="stat-card">
    <div class="stat-num"><?= $counts['perkerjaan'] ?></div>
    <div class="stat-label">Perkerjaan</div>
  </div>
  <div class="stat-card">
    <div class="stat-num"><?= $counts['sertifikat'] ?></div>
    <div class="stat-label">Sertifikat</div>
  </div>
  <div class="stat-card">
    <div class="stat-num"><?= $counts['galery'] ?></div>
    <div class="stat-label">Galeri</div>
  </div>
</div>

<div class="card">
  <div class="card-header"><h2>Menu Cepat</h2></div>
  <div class="card-body" style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:1rem;">
    <a href="site-setup.php" class="btn btn-secondary" style="justify-content:center;padding:1.25rem;">⚙ Site Setup</a>
    <a href="about-me.php" class="btn btn-secondary" style="justify-content:center;padding:1.25rem;">👤 About Me</a>
    <a href="pengalaman.php" class="btn btn-secondary" style="justify-content:center;padding:1.25rem;">📋 Pengalaman</a>
    <a href="perkerjaan.php" class="btn btn-secondary" style="justify-content:center;padding:1.25rem;">💼 Perkerjaan</a>
    <a href="sertifikat.php" class="btn btn-secondary" style="justify-content:center;padding:1.25rem;">🏆 Sertifikat</a>
    <a href="galery.php" class="btn btn-secondary" style="justify-content:center;padding:1.25rem;">🖼 Galeri</a>
  </div>
</div>

<?php require_once 'footer.php'; ?>
