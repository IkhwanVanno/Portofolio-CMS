<?php
$pageTitle = 'Pengalaman';
require_once '../includes/config.php';
requireLogin();

$db = getDB();
$action = $_GET['action'] ?? 'list';
$id = (int) ($_GET['id'] ?? 0);

// DELETE
if ($action === 'delete' && $id) {
  $row = $db->prepare("SELECT image FROM pengalaman WHERE id = ?");
  $row->execute([$id]);
  $r = $row->fetch();
  if ($r)
    deleteImage($r['image']);
  $db->prepare("DELETE FROM pengalaman WHERE id = ?")->execute([$id]);
  flash('Data berhasil dihapus.', 'success');
  header('Location: pengalaman.php');
  exit;
}

// SAVE (create/update)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $title = trim($_POST['title'] ?? '');
  $desc = trim($_POST['description'] ?? '');
  $imgPath = '';

  if (!empty($_FILES['image']['name'])) {
    $imgPath = uploadImage($_FILES['image'], 'experience');
  }

  if ($id) {
    // Update
    $existing = $db->prepare("SELECT image FROM pengalaman WHERE id = ?");
    $existing->execute([$id]);
    $cur = $existing->fetch();
    if ($imgPath && $cur['image'])
      deleteImage($cur['image']);
    if (!$imgPath)
      $imgPath = $cur['image'] ?? '';

    $stmt = $db->prepare("UPDATE pengalaman SET title=?, description=?, image=? WHERE id=?");
    $stmt->execute([$title, $desc, $imgPath, $id]);
    flash('Pengalaman berhasil diperbarui!');
  } else {
    // Create
    $stmt = $db->prepare("INSERT INTO pengalaman (title, description, image) VALUES (?,?,?)");
    $stmt->execute([$title, $desc, $imgPath]);
    flash('Pengalaman berhasil ditambahkan!');
  }
  header('Location: pengalaman.php');
  exit;
}

// EDIT — fetch single
$edit = null;
if ($action === 'edit' && $id) {
  $stmt = $db->prepare("SELECT * FROM pengalaman WHERE id = ?");
  $stmt->execute([$id]);
  $edit = $stmt->fetch();
}

// LIST
$items = $db->query("SELECT * FROM pengalaman ORDER BY created_at DESC")->fetchAll();
require_once 'header.php';
?>

<?php if ($action === 'add' || $action === 'edit'): ?>
  <div class="card">
    <div class="card-header">
      <h2><?= $action === 'edit' ? 'Edit Pengalaman' : 'Tambah Pengalaman' ?></h2>
      <a href="pengalaman.php" class="btn btn-secondary btn-sm">← Kembali</a>
    </div>
    <div class="card-body">
      <form method="POST" enctype="multipart/form-data" action="pengalaman.php<?= $id ? '?id=' . $id : '' ?>">
        <div class="form-grid">
          <div class="form-group full">
            <label>Judul</label>
            <input type="text" name="title" value="<?= htmlspecialchars($edit['title'] ?? '') ?>" required
              placeholder="Nama pengalaman...">
          </div>
          <div class="form-group full">
            <label>Deskripsi</label>
            <textarea name="description" rows="5"
              placeholder="Deskripsi pengalaman..."><?= htmlspecialchars($edit['description'] ?? '') ?></textarea>
          </div>
          <div class="form-group">
            <label>Gambar</label>
            <input type="file" name="image" accept="image/*">
            <?php if (!empty($edit['image'])): ?>
              <div class="current-img"><img src="../<?= htmlspecialchars($edit['image']) ?>" alt="img"></div>
            <?php endif; ?>
          </div>
        </div>
        <div class="form-actions">
          <button type="submit" class="btn btn-primary">💾 Simpan</button>
          <a href="pengalaman.php" class="btn btn-secondary">Batal</a>
        </div>
      </form>
    </div>
  </div>

<?php else: ?>

  <div style="display:flex;justify-content:flex-end;margin-bottom:1rem;">
    <a href="?action=add" class="btn btn-primary">+ Tambah Pengalaman</a>
  </div>

  <div class="card">
    <div class="card-header">
      <h2>Daftar Pengalaman</h2>
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
              <td class="td-img">
                <?php if ($item['image']): ?>
                  <img src="../<?= htmlspecialchars($item['image']) ?>" alt="">
                <?php else: ?>
                  <span class="text-muted">—</span>
                <?php endif; ?>
              </td>
              <td><?= htmlspecialchars($item['title']) ?></td>
              <td class="text-muted"><?= htmlspecialchars(mb_substr($item['description'], 0, 80)) ?>...</td>
              <td>
                <div class="actions">
                  <a href="?action=edit&id=<?= $item['id'] ?>" class="btn btn-secondary btn-sm">✏ Edit</a>
                  <a href="?action=delete&id=<?= $item['id'] ?>" class="btn btn-danger btn-sm"
                    onclick="return confirm('Hapus data ini?')">🗑</a>
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