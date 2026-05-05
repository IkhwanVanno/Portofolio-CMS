<?php
$pageTitle = 'Galeri';
require_once '../includes/config.php';
requireLogin();

$db = getDB();
$action = $_GET['action'] ?? 'list';
$id = (int) ($_GET['id'] ?? 0);

// DELETE
if ($action === 'delete' && $id) {
  $row = $db->prepare("SELECT image FROM galery WHERE id = ?");
  $row->execute([$id]);
  $r = $row->fetch();
  if ($r)
    deleteImage($r['image']);
  $db->prepare("DELETE FROM galery WHERE id = ?")->execute([$id]);
  flash('Foto berhasil dihapus.');
  header('Location: galery.php');
  exit;
}

// ADD
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $uploaded = 0;
  if (!empty($_FILES['images']['name'][0])) {
    foreach ($_FILES['images']['tmp_name'] as $i => $tmp) {
      if ($_FILES['images']['error'][$i] === UPLOAD_ERR_OK) {
        $file = [
          'name' => $_FILES['images']['name'][$i],
          'type' => $_FILES['images']['type'][$i],
          'tmp_name' => $tmp,
          'error' => $_FILES['images']['error'][$i],
          'size' => $_FILES['images']['size'][$i],
        ];
        $path = uploadImage($file, 'gallery');
        if ($path) {
          $db->prepare("INSERT INTO galery (image) VALUES (?)")->execute([$path]);
          $uploaded++;
        }
      }
    }
  }
  flash("$uploaded foto berhasil diupload!");
  header('Location: galery.php');
  exit;
}

$items = $db->query("SELECT * FROM galery ORDER BY id DESC")->fetchAll();
require_once 'header.php';
?>

<div class="card" style="margin-bottom:1.5rem;">
  <div class="card-header">
    <h2>Upload Foto Galeri</h2>
  </div>
  <div class="card-body">
    <form method="POST" enctype="multipart/form-data">
      <div class="form-group">
        <label>Pilih Foto (bisa multiple)</label>
        <input type="file" name="images[]" accept="image/*" multiple>
        <span class="text-muted" style="margin-top:0.35rem;">Pilih beberapa foto sekaligus</span>
      </div>
      <div class="form-actions">
        <button type="submit" class="btn btn-primary">📤 Upload</button>
      </div>
    </form>
  </div>
</div>

<div class="card">
  <div class="card-header">
    <h2>Foto Galeri (<?= count($items) ?>)</h2>
  </div>
  <div class="card-body">
    <?php if (empty($items)): ?>
      <p style="color:rgba(200,208,224,0.3);text-align:center;padding:2rem;">Belum ada foto.</p>
    <?php else: ?>
      <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(160px,1fr));gap:1rem;">
        <?php foreach ($items as $item): ?>
          <div style="position:relative;border-radius:2px;overflow:hidden;border:1px solid rgba(255,255,255,0.08);">
            <img src="../<?= htmlspecialchars($item['image']) ?>" alt=""
              style="width:100%;height:130px;object-fit:cover;display:block;">
            <a href="?action=delete&id=<?= $item['id'] ?>" onclick="return confirm('Hapus foto ini?')"
              style="position:absolute;top:6px;right:6px;background:rgba(224,92,109,0.85);color:#fff;border:none;width:26px;height:26px;border-radius:50%;display:flex;align-items:center;justify-content:center;cursor:pointer;font-size:0.7rem;text-decoration:none;">✕</a>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</div>

<?php require_once 'footer.php'; ?>