<?php
require_once __DIR__ . '/config/constants.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/config/session.php';
require_once __DIR__ . '/includes/functions.php';

// ... rest of login logic

if (isLoggedIn()) { header('Location: dashboard.php'); exit; }

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim((string)($_POST['username'] ?? ''));
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $error = 'Please enter username and password.';
    } else {
        $user = dbFetchOne(
            "SELECT * FROM users WHERE LOWER(username) = LOWER(:u) AND is_active = 1 LIMIT 1",
            [':u' => $username]
        );

        $storedPassword = $user['password_hash'] ?? '';
        $isValidPassword = ($storedPassword !== '' && ($password === $storedPassword || password_verify($password, $storedPassword)));

        if ($user && $isValidPassword) {
            session_regenerate_id(true);
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['first_name'] = $user['first_name'];
            $_SESSION['last_name'] = $user['last_name'];
            $_SESSION['user_role'] = $user['role'];
            $_SESSION['collection_center_id'] = $user['collection_center_id'];
            dbQuery("UPDATE users SET last_login = NOW() WHERE id = :id", [':id' => $user['id']]);
            logAudit('login', 'users', $user['id']);
            header('Location: dashboard.php');
            exit;
        }

        $error = 'Invalid username or password.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Login | <?= SITE_SHORT_NAME ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<link href="<?= ASSETS_URL ?>css/style.css" rel="stylesheet">
</head>
<body class="login-body">
<div class="login-container">
  <div class="card login-card shadow-lg">
    <div class="card-body p-5">
      <div class="text-center mb-4">
        <i class="fas fa-leaf fa-3x text-success"></i>
        <h3 class="mt-3"><?= SITE_SHORT_NAME ?></h3>
        <p class="text-muted"><?= COMPANY_NAME ?></p>
      </div>
      <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
      <?php endif; ?>
      <?php if (isset($_GET['timeout'])): ?>
        <div class="alert alert-warning">Session expired. Please log in again.</div>
      <?php endif; ?>
      <form method="POST" autocomplete="off">
        <div class="mb-3">
          <label class="form-label">Username</label>
          <div class="input-group">
            <span class="input-group-text"><i class="fas fa-user"></i></span>
            <input type="text" name="username" class="form-control" required>
          </div>
        </div>
        <div class="mb-3">
          <label class="form-label">Password</label>
          <div class="input-group">
            <span class="input-group-text"><i class="fas fa-lock"></i></span>
            <input type="password" name="password" class="form-control" required>
          </div>
        </div>
        <button class="btn btn-success w-100 py-2"><i class="fas fa-sign-in-alt"></i> Login</button>
      </form>
      <p class="text-center text-muted mt-4 mb-0 small">© <?= date('Y') ?> <?= COMPANY_NAME ?></p>
    </div>
  </div>
</div>
</body>
</html>