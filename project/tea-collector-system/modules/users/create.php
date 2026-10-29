<?php
define('TCDMS', true);
$pageTitle = 'Add User';
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/sidebar.php';
requireRole('admin');

$centers = dbFetchAll("SELECT id, name FROM collection_centers WHERE is_active = 1");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'];
    if (strlen($password) < 6) { $error = 'Password must be at least 6 characters.'; }
    else {
        try {
            $data = [
                ':username' => sanitize($_POST['username']),
                ':email' => sanitize($_POST['email']),
                ':hash' => $password,
                ':first' => sanitize($_POST['first_name']),
                ':last' => sanitize($_POST['last_name']),
                ':phone' => sanitize($_POST['phone']),
                ':role' => $_POST['role'],
                ':center' => $_POST['collection_center_id'] ?: null
            ];
            $id = dbInsert("INSERT INTO users (username,email,password_hash,first_name,last_name,phone,role,collection_center_id)
                VALUES (:username,:email,:hash,:first,:last,:phone,:role,:center)", $data);
            logAudit('create', 'users', $id);
            redirectWith('index.php', 'success', 'User created!');
        } catch (PDOException $e) { $error = 'Error: '.$e->getMessage(); }
    }
}
?>
<div class="form-section">
  <h5><i class="fas fa-user-plus text-success"></i> Add New User</h5>
  <?php if (!empty($error)): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>
  <form method="POST">
    <div class="row g-3">
      <div class="col-md-6"><label class="form-label">Username *</label>
        <input type="text" name="username" class="form-control" required></div>
      <div class="col-md-6"><label class="form-label">Email *</label>
        <input type="email" name="email" class="form-control" required></div>
      <div class="col-md-6"><label class="form-label">First Name *</label>
        <input type="text" name="first_name" class="form-control" required></div>
      <div class="col-md-6"><label class="form-label">Last Name *</label>
        <input type="text" name="last_name" class="form-control" required></div>
      <div class="col-md-6"><label class="form-label">Phone</label>
        <input type="text" name="phone" class="form-control"></div>
      <div class="col-md-6"><label class="form-label">Password *</label>
        <input type="password" name="password" class="form-control" required></div>
      <div class="col-md-6"><label class="form-label">Role *</label>
        <select name="role" class="form-select" required>
          <option value="admin">Admin</option><option value="manager">Manager</option>
          <option value="collector">Collector</option><option value="accountant">Accountant</option>
          <option value="transport_officer">Transport Officer</option><option value="storekeeper">Storekeeper</option>
        </select></div>
      <div class="col-md-6"><label class="form-label">Collection Center</label>
        <select name="collection_center_id" class="form-select">
          <option value="">-- None --</option>
          <?php foreach ($centers as $c): ?>
            <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['name']) ?></option>
          <?php endforeach; ?>
        </select></div>
    </div>
    <div class="mt-4">
      <button class="btn btn-success"><i class="fas fa-save"></i> Create User</button>
      <a href="index.php" class="btn btn-secondary">Cancel</a>
    </div>
  </form>
</div>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>