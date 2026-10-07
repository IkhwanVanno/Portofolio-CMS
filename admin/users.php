<?php
$pageTitle = 'Users';
require_once '../includes/config.php';
requireLogin();

$db = getDB();
$action = $_GET['action'] ?? 'list';
$id = (int) ($_GET['id'] ?? 0);

// DELETE
if ($action === 'delete' && $id) {
  $total = $db->query("SELECT COUNT(*) FROM users")->fetchColumn();
  if ($total <= 1) {
    flash('Tidak bisa menghapus user terakhir!', 'error');
  } else {
    $db->prepare("DELETE FROM users WHERE id = ?")->execute([$id]);
    flash('User berhasil dihapus.');
  }
  header('Location: users.php');
  exit;
}

// SAVE
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = trim($_POST['email'] ?? '');
  $username = trim($_POST['username'] ?? '');
  $password = $_POST['password'] ?? '';

  if ($id) {
    // Update — password optional
    if ($password) {
      $hash = password_hash($password, PASSWORD_DEFAULT);
      $db->prepare("UPDATE users SET email=?, username=?, password=? WHERE id=?")->execute([$email, $username, $hash, $id]);
    } else {
      $db->prepare("UPDATE users SET email=?, username=? WHERE id=?")->execute([$email, $username, $id]);
    }
    flash('User berhasil diperbarui!');
  } else {
    if (!$password) {
      flash('Password wajib diisi untuk user baru!', 'error');
      header('Location: users.php?action=add');
      exit;
    }
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $db->prepare("INSERT INTO users (email, username, password) VALUES (?,?,?)")->execute([$email, $username, $hash]);
    flash('User berhasil ditambahkan!');
  }
  header('Location: users.php');
  exit;
}

$edit = null;
if ($action === 'edit' && $id) {
  $stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
  $stmt->execute([$id]);
  $edit = $stmt->fetch();
}
$items = $db->query("SELECT id, email, username, created_at FROM users ORDER BY id")->fetchAll();
require_once 'header.php';
?>

<?php if ($action === 'add' || $action === 'edit'): ?>
  <div class="card">
    <div class="card-header">
      <h2><?= $action === 'edit' ? 'Edit User' : 'Tambah User' ?></h2>
      <a href="users.php" class="btn btn-secondary btn-sm">← Kembali</a>
    </div>
    <div class="card-body">
      <form method="POST" action="users.php<?= $id ? '?id=' . $id : '' ?>">
        <div class="form-grid">
          <div class="form-group">
            <label>Username</label>
            <input type="text" name="username" value="<?= htmlspecialchars($edit['username'] ?? '') ?>" required>
          </div>
          <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" value="<?= htmlspecialchars($edit['email'] ?? '') ?>" required>
          </div>
          <div class="form-group full">
            <label>Password <?= $action === 'edit' ? '(kosongkan jika tidak diubah)' : '' ?></label>
            <input type="password" name="password" <?= $action === 'add' ? 'required' : '' ?> placeholder="••••••••">
          </div>
        </div>
        <div class="form-actions">
          <button type="submit" class="btn btn-primary">💾 Simpan</button>
          <a href="users.php" class="btn btn-secondary">Batal</a>
        </div>
      </form>
    </div>
  </div>

<?php else: ?>
  <div style="display:flex;justify-content:flex-end;margin-bottom:1rem;">
    <a href="?action=add" class="btn btn-primary">+ Tambah User</a>
  </div>
  <div class="card">
    <div class="card-header">
      <h2>Daftar User</h2>
    </div>
    <table>
      <thead>
        <tr>
          <th>Username</th>
          <th>Email</th>
          <th>Dibuat</th>
          <th style="width:140px;">Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($items as $item): ?>
          <tr>
            <td><?= htmlspecialchars($item['username']) ?></td>
            <td><?= htmlspecialchars($item['email']) ?></td>
            <td class="text-muted"><?= date('d M Y', strtotime($item['created_at'])) ?></td>
            <td>
              <div class="actions">
                <a href="?action=edit&id=<?= $item['id'] ?>" class="btn btn-secondary btn-sm">✏ Edit</a>
                <?php if ($item['id'] != $_SESSION['admin_id']): ?>
                  <a href="?action=delete&id=<?= $item['id'] ?>" class="btn btn-danger btn-sm"
                    onclick="return confirm('Hapus user ini?')">🗑</a>
                <?php endif; ?>
              </div>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
<?php endif; ?>

<?php require_once 'footer.php'; ?>