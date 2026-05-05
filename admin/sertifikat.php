<?php
$pageTitle = 'Sertifikat';
require_once '../includes/config.php';
requireLogin();

$db = getDB();
$action = $_GET['action'] ?? 'list';
$id = (int) ($_GET['id'] ?? 0);

if ($action === 'delete' && $id) {
  $row = $db->prepare("SELECT image FROM sertifikat WHERE id = ?");
  $row->execute([$id]);
  $r = $row->fetch();
  if ($r)
    deleteImage($r['image']);
  $db->prepare("DELETE FROM sertifikat WHERE id = ?")->execute([$id]);
  flash('Data berhasil dihapus.');
  header('Location: sertifikat.php');
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $title = trim($_POST['title'] ?? '');
  $desc = trim($_POST['description'] ?? '');
  $imgPath = '';

  if (!empty($_FILES['image']['name'])) {
    $imgPath = uploadImage($_FILES['image'], 'certificate');
  }

  if ($id) {
    $existing = $db->prepare("SELECT image FROM sertifikat WHERE id = ?");
    $existing->execute([$id]);
    $cur = $existing->fetch();
    if ($imgPath && $cur['image'])
      deleteImage($cur['image']);
    if (!$imgPath)
      $imgPath = $cur['image'] ?? '';
    $db->prepare("UPDATE sertifikat SET title=?, description=?, image=? WHERE id=?")->execute([$title, $desc, $imgPath, $id]);
    flash('Sertifikat berhasil diperbarui!');
  } else {
    $db->prepare("INSERT INTO sertifikat (title, description, image) VALUES (?,?,?)")->execute([$title, $desc, $imgPath]);
    flash('Sertifikat berhasil ditambahkan!');
  }
  header('Location: sertifikat.php');
  exit;
}

$edit = null;
if ($action === 'edit' && $id) {
  $stmt = $db->prepare("SELECT * FROM sertifikat WHERE id = ?");
  $stmt->execute([$id]);
  $edit = $stmt->fetch();
}
$items = $db->query("SELECT * FROM sertifikat ORDER BY created_at DESC")->fetchAll();
require_once 'header.php';
?>

<?php if ($action === 'add' || $action === 'edit'): ?>
  <div class="card">
    <div class="card-header">
      <h2><?= $action === 'edit' ? 'Edit Sertifikat' : 'Tambah Sertifikat' ?></h2>
      <a href="sertifikat.php" class="btn btn-secondary btn-sm">← Kembali</a>
    </div>
    <div class="card-body">
      <form method="POST" enctype="multipart/form-data" action="sertifikat.php<?= $id ? '?id=' . $id : '' ?>">
        <div class="form-grid">
          <div class="form-group full">
            <label>Judul Sertifikat</label>
            <input type="text" name="title" value="<?= htmlspecialchars($edit['title'] ?? '') ?>" required>
          </div>
          <div class="form-group full">
            <label>Deskripsi</label>
            <textarea name="description" rows="5"><?= htmlspecialchars($edit['description'] ?? '') ?></textarea>
          </div>
          <div class="form-group">
            <label>Gambar Sertifikat</label>
            <input type="file" name="image" accept="image/*">
            <?php if (!empty($edit['image'])): ?>
              <div class="current-img"><img src="../<?= htmlspecialchars($edit['image']) ?>" alt="img"></div>
            <?php endif; ?>
          </div>
        </div>
        <div class="form-actions">
          <button type="submit" class="btn btn-primary">💾 Simpan</button>
          <a href="sertifikat.php" class="btn btn-secondary">Batal</a>
        </div>
      </form>
    </div>
  </div>

<?php else: ?>
  <div style="display:flex;justify-content:flex-end;margin-bottom:1rem;">
    <a href="?action=add" class="btn btn-primary">+ Tambah Sertifikat</a>
  </div>
  <div class="card">
    <div class="card-header">
      <h2>Daftar Sertifikat</h2>
    </div>
    <table>
      <thead>
        <tr>
          <th style="width:70px;">Gambar</th>
          <th>Judul</th>
          <th>Deskripsi</th>
          <th style="width:140px;">Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($items)): ?>
          <tr>
            <td colspan="4" style="text-align:center;padding:2rem;color:rgba(200,208,224,0.3);">Belum ada data.</td>
          </tr>
        <?php else: ?>
          <?php foreach ($items as $item): ?>
            <tr>
              <td class="td-img"><?php if ($item['image']): ?><img src="../<?= htmlspecialchars($item['image']) ?>"
                    alt=""><?php else: ?><span class="text-muted">—</span><?php endif; ?></td>
              <td><?= htmlspecialchars($item['title']) ?></td>
              <td class="text-muted"><?= htmlspecialchars(mb_substr($item['description'], 0, 80)) ?>...</td>
              <td>
                <div class="actions">
                  <a href="?action=edit&id=<?= $item['id'] ?>" class="btn btn-secondary btn-sm">✏ Edit</a>
                  <a href="?action=delete&id=<?= $item['id'] ?>" class="btn btn-danger btn-sm"
                    onclick="return confirm('Hapus?')">🗑</a>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
<?php endif; ?>

<?php require_once 'footer.php'; ?>