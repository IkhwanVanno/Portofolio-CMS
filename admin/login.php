<?php
require_once '../includes/config.php';

if (isLoggedIn()) {
    header('Location: index.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if ($email && $password) {
        $db = getDB();
        $stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['admin_id'] = $user['id'];
            $_SESSION['admin_name'] = $user['username'];
            header('Location: index.php');
            exit;
        } else {
            $error = 'Email atau password salah.';
        }
    } else {
        $error = 'Mohon isi semua field.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Login</title>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;600&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    :root {
      --blue: #1B2B4B; --gold: #C9A96E; --cream: #F5F0E8;
      --font-display: 'Cormorant Garamond', serif; --font-body: 'DM Sans', sans-serif;
    }
    body {
      font-family: var(--font-body); background: var(--blue);
      min-height: 100vh; display: flex; align-items: center; justify-content: center;
      background-image: radial-gradient(ellipse at top, #2A3F6B 0%, #1B2B4B 60%);
    }
    .login-card {
      background: rgba(245,240,232,0.04); border: 1px solid rgba(201,169,110,0.25);
      backdrop-filter: blur(20px); border-radius: 4px;
      padding: 3rem; width: 100%; max-width: 420px;
      box-shadow: 0 40px 80px rgba(0,0,0,0.4);
    }
    .login-logo {
      font-family: var(--font-display); font-size: 2.5rem; font-weight: 300;
      color: var(--gold); text-align: center; margin-bottom: 0.5rem; letter-spacing: 0.03em;
    }
    .login-sub {
      text-align: center; color: rgba(245,240,232,0.4); font-size: 0.8rem;
      letter-spacing: 0.15em; text-transform: uppercase; margin-bottom: 2.5rem;
    }
    .form-group { margin-bottom: 1.25rem; }
    label { display: block; color: rgba(245,240,232,0.6); font-size: 0.75rem; letter-spacing: 0.1em; text-transform: uppercase; margin-bottom: 0.5rem; }
    input {
      width: 100%; padding: 0.85rem 1rem; background: rgba(255,255,255,0.06);
      border: 1px solid rgba(201,169,110,0.2); border-radius: 2px;
      color: var(--cream); font-family: var(--font-body); font-size: 0.95rem;
      transition: border-color 0.3s;
    }
    input:focus { outline: none; border-color: var(--gold); background: rgba(255,255,255,0.09); }
    input::placeholder { color: rgba(245,240,232,0.25); }
    .btn-login {
      width: 100%; padding: 0.9rem; background: var(--gold);
      border: none; border-radius: 2px; color: var(--blue);
      font-family: var(--font-body); font-size: 0.9rem; font-weight: 500;
      letter-spacing: 0.1em; text-transform: uppercase; cursor: pointer;
      transition: background 0.3s, transform 0.2s; margin-top: 0.5rem;
    }
    .btn-login:hover { background: #e8c99a; transform: translateY(-1px); }
    .error {
      background: rgba(220,53,69,0.15); border: 1px solid rgba(220,53,69,0.4);
      color: #f8a5a5; padding: 0.75rem 1rem; border-radius: 2px;
      font-size: 0.85rem; margin-bottom: 1.25rem;
    }
    .back-link { text-align: center; margin-top: 1.5rem; }
    .back-link a { color: rgba(201,169,110,0.6); font-size: 0.8rem; text-decoration: none; }
    .back-link a:hover { color: var(--gold); }
  </style>
</head>
<body>
  <div class="login-card">
    <div class="login-logo">Admin</div>
    <div class="login-sub">Portfolio Management</div>

    <?php if ($error): ?>
      <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" action="">
      <div class="form-group">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" placeholder="admin@example.com" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
      </div>
      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" placeholder="••••••••" required>
      </div>
      <button type="submit" class="btn-login">Masuk</button>
    </form>
    <div class="back-link"><a href="../index.php">← Kembali ke Portfolio</a></div>
  </div>
</body>
</html>
