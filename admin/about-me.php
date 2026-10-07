<?php
$pageTitle = 'About Me';
require_once '../includes/config.php';
requireLogin();

$db = getDB();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fields = ['title', 'description', 'intro', 'whatsapp', 'whatsapp_link', 'instagram', 'instagram_link', 'email', 'github', 'linkedin'];
    $data = [];
    foreach ($fields as $f) $data[$f] = trim($_POST[$f] ?? '');

    $profile = '';
    if (!empty($_FILES['profile']['name'])) {
        $profile = uploadImage($_FILES['profile'], 'profile');
    }
    $bg_intro = '';
    if (!empty($_FILES['bg_intro']['name'])) {
        $bg_intro = uploadImage($_FILES['bg_intro'], 'profile');
    }

    $sets = implode(', ', array_map(function($f) {
    return "$f = :$f";
    }, $fields));
    if ($profile) $sets .= ', profile = :profile';
    if ($bg_intro) $sets .= ', bg_intro = :bg_intro';

    $stmt = $db->prepare("UPDATE about_me SET $sets WHERE id = 1");
    foreach ($data as $k => $v) $stmt->bindValue(":$k", $v);
    if ($profile) $stmt->bindValue(':profile', $profile);
    if ($bg_intro) $stmt->bindValue(':bg_intro', $bg_intro);
    $stmt->execute();

    flash('About Me berhasil diperbarui!');
    header('Location: about-me.php');
    exit;
}

$about = $db->query("SELECT * FROM about_me WHERE id = 1")->fetch();
require_once 'header.php';
?>

<div class="card">
  <div class="card-header"><h2>About Me</h2><span class="badge badge-gold">Single Row</span></div>
  <div class="card-body">
    <form method="POST" enctype="multipart/form-data">
      <div class="form-grid">
        <div class="form-group">
          <label>Judul (About Section)</label>
          <input type="text" name="title" value="<?= htmlspecialchars($about['title'] ?? '') ?>" placeholder="About Me">
        </div>
        <div class="form-group">
          <label>Teks Intro (Hero Section)</label>
          <input type="text" name="intro" value="<?= htmlspecialchars($about['intro'] ?? '') ?>" placeholder="Hello, I'm a Developer">
        </div>
        <div class="form-group full">
          <label>Deskripsi</label>
          <textarea name="description" rows="5"><?= htmlspecialchars($about['description'] ?? '') ?></textarea>
        </div>
        <div class="form-group">
          <label>Foto Profil</label>
          <input type="file" name="profile" accept="image/*">
          <?php if (!empty($about['profile'])): ?>
            <div class="current-img"><img src="../<?= htmlspecialchars($about['profile']) ?>" alt="Profile"></div>
          <?php endif; ?>
        </div>
        <div class="form-group">
          <label>Background Intro (Hero)</label>
          <input type="file" name="bg_intro" accept="image/*">
          <?php if (!empty($about['bg_intro'])): ?>
            <div class="current-img"><img src="../<?= htmlspecialchars($about['bg_intro']) ?>" alt="BG Intro" style="width:120px;height:70px;object-fit:cover;"></div>
          <?php endif; ?>
        </div>
      </div>

      <hr style="border:none;border-top:1px solid rgba(255,255,255,0.06);margin:1.5rem 0;">
      <p style="font-size:0.8rem;color:rgba(200,208,224,0.4);margin-bottom:1rem;">Kontak</p>
      <div class="form-grid">
        <div class="form-group">
          <label>WhatsApp (nomor)</label>
          <input type="text" name="whatsapp" value="<?= htmlspecialchars($about['whatsapp'] ?? '') ?>" placeholder="+62 812 xxx">
        </div>
        <div class="form-group">
          <label>WhatsApp Link</label>
          <input type="url" name="whatsapp_link" value="<?= htmlspecialchars($about['whatsapp_link'] ?? '') ?>" placeholder="https://wa.me/628...">
        </div>
        <div class="form-group">
          <label>Instagram (username)</label>
          <input type="text" name="instagram" value="<?= htmlspecialchars($about['instagram'] ?? '') ?>" placeholder="@username">
        </div>
        <div class="form-group">
          <label>Instagram Link</label>
          <input type="url" name="instagram_link" value="<?= htmlspecialchars($about['instagram_link'] ?? '') ?>" placeholder="https://instagram.com/...">
        </div>
        <div class="form-group">
          <label>Email</label>
          <input type="email" name="email" value="<?= htmlspecialchars($about['email'] ?? '') ?>" placeholder="you@example.com">
        </div>
        <div class="form-group">
          <label>GitHub URL</label>
          <input type="url" name="github" value="<?= htmlspecialchars($about['github'] ?? '') ?>" placeholder="https://github.com/...">
        </div>
        <div class="form-group">
          <label>LinkedIn URL</label>
          <input type="url" name="linkedin" value="<?= htmlspecialchars($about['linkedin'] ?? '') ?>" placeholder="https://linkedin.com/in/...">
        </div>
      </div>

      <div class="form-actions">
        <button type="submit" class="btn btn-primary">💾 Simpan Perubahan</button>
      </div>
    </form>
  </div>
</div>

<?php require_once 'footer.php'; ?>
