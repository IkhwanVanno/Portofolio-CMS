<?php
$pageTitle = 'Site Setup';
require_once '../includes/config.php';
requireLogin();

$db = getDB();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $fields = [
    'title',
    'footer',
    'sub_one',
    'sub_one_desc',
    'sub_two',
    'sub_two_desc',
    'sub_three',
    'sub_three_desc',
    'title_pengalaman',
    'desc_pengalaman',
    'title_perkerjaan',
    'desc_perkerjaan',
    'title_sertifikat',
    'desc_sertifikat',
    'title_galeri',
    'desc_galeri',
    'title_kontak',
    'desc_kontak'
  ];

  $data = [];
  foreach ($fields as $f)
    $data[$f] = trim($_POST[$f] ?? '');

  $sets = implode(', ', array_map(function($f) {
    return "$f = :$f";
  }, $fields));
  $stmt = $db->prepare("UPDATE site_setup SET $sets WHERE id = 1");

  foreach ($data as $k => $v)
    $stmt->bindValue(":$k", $v);
  $stmt->execute();

  flash('Site setup berhasil diperbarui!');
  header('Location: site-setup.php');
  exit;
}

$site = $db->query("SELECT * FROM site_setup WHERE id = 1")->fetch();
require_once 'header.php';
?>

<div class="card">
  <div class="card-header">
    <h2>Pengaturan Situs</h2><span class="badge badge-gold">Single Row</span>
  </div>
  <div class="card-body">
    <form method="POST" enctype="multipart/form-data">
      <div class="form-grid">
        <div class="form-group">
          <label>Judul Situs</label>
          <input type="text" name="title" value="<?= htmlspecialchars($site['title']) ?>" placeholder="My Portfolio">
        </div>
        <div class="form-group">
          <label>Footer Text</label>
          <input type="text" name="footer" value="<?= htmlspecialchars($site['footer']) ?>"
            placeholder="© 2025 All Rights Reserved">
        </div>
      </div>

      <hr style="border:none;border-top:1px solid rgba(255,255,255,0.06);margin:1.5rem 0;">
      <p style="font-size:0.8rem;color:rgba(200,208,224,0.4);margin-bottom:1rem;">Bagian About Me — Sub Konten</p>
      <div class="form-grid">
        <div class="form-group">
          <label>Sub One Judul</label>
          <input type="text" name="sub_one" value="<?= htmlspecialchars($site['sub_one']) ?>">
        </div>
        <div class="form-group">
          <label>Sub One Deskripsi</label>
          <textarea name="sub_one_desc"><?= htmlspecialchars($site['sub_one_desc']) ?></textarea>
        </div>
        <div class="form-group">
          <label>Sub Two Judul</label>
          <input type="text" name="sub_two" value="<?= htmlspecialchars($site['sub_two']) ?>">
        </div>
        <div class="form-group">
          <label>Sub Two Deskripsi</label>
          <textarea name="sub_two_desc"><?= htmlspecialchars($site['sub_two_desc']) ?></textarea>
        </div>
        <div class="form-group">
          <label>Sub Three Judul</label>
          <input type="text" name="sub_three" value="<?= htmlspecialchars($site['sub_three']) ?>">
        </div>
        <div class="form-group">
          <label>Sub Three Deskripsi</label>
          <textarea name="sub_three_desc"><?= htmlspecialchars($site['sub_three_desc']) ?></textarea>
        </div>
      </div>

      <hr style="border:none;border-top:1px solid rgba(255,255,255,0.06);margin:1.5rem 0;">
      <p style="font-size:0.8rem;color:rgba(200,208,224,0.4);margin-bottom:1rem;">Judul & Deskripsi Section</p>
      <div class="form-grid">
        <?php
        $sections = [
          ['label' => 'Pengalaman', 'key' => 'pengalaman'],
          ['label' => 'Perkerjaan', 'key' => 'perkerjaan'],
          ['label' => 'Sertifikat', 'key' => 'sertifikat'],
          ['label' => 'Galeri', 'key' => 'galeri'],
          ['label' => 'Kontak', 'key' => 'kontak'],
        ];
        foreach ($sections as $s):
          ?>
          <div class="form-group">
            <label>Judul <?= $s['label'] ?></label>
            <input type="text" name="title_<?= $s['key'] ?>"
              value="<?= htmlspecialchars($site['title_' . $s['key']] ?? '') ?>">
          </div>
          <div class="form-group">
            <label>Deskripsi <?= $s['label'] ?></label>
            <textarea name="desc_<?= $s['key'] ?>"><?= htmlspecialchars($site['desc_' . $s['key']] ?? '') ?></textarea>
          </div>
        <?php endforeach; ?>
      </div>

      <div class="form-actions">
        <button type="submit" class="btn btn-primary">💾 Simpan Perubahan</button>
      </div>
    </form>
  </div>
</div>

<?php require_once 'footer.php'; ?>