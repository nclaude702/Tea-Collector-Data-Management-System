<?php
define('TCDMS', true);
$pageTitle = 'Edit User';
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/sidebar.php';
requireRole('admin');

$id = (int)($_GET['id'] ?? 0);
$u = dbFetchOne("SELECT * FROM users WHERE id = :id", [':id' => $id]);
if (!$u) { redirectWith('index.php', 'error', 'User not found.'); }

$centers = dbFetchAll("SELECT id, name FROM collection_centers WHERE is_active = 1");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        ':username' => sanitize($_POST['username']),
        ':email' => sanitize($_POST['email']),
        ':first' => sanitize($_POST['first_name']),
        ':last' => sanitize($_POST['last_name']),
        ':phone' => sanitize($_POST['phone']),
        ':role' => $_POST['role'],
        ':center' => $_POST['collection_center_id'] ?: null,
        ':active' => isset($_POST['is_active']) ? 1 : 0,
        ':id' => $id
    ];
    dbQuery("UPDATE users SET username=:username, email=:email, first_name=:first, last_name=:last,
             phone=:phone, role=:role, collection_center_id=:center, is_active=:active WHERE id=:id", $data);
    if (!empty($_POST['password'])) {
        dbQuery("UPDATE users SET password_hash=:h WHERE id=:id", [':h' => trim($_POST['password']), ':id' => $id]);
    }
    logAudit('update', 'users', $id);
    redirectWith('index.php', 'success', 'User updated!');
}
?>
<div class="form-section">
  <h5><i class="fas fa-user-edit text-warning"></i> Edit User: <?= htmlspecialchars($u['username']) ?></h5>
  <form method="POST">
    <div class="row g-3">
      <div class="col-md-6"><label class="form-label">Username *</label>
        <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($u['username']) ?>" required></div>
      <div class="col-md-6"><label class="form-label">Email *</label>
        <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($u['email']) ?>" required></div>
      <div class="col-md-6"><label class="form-label">First Name *</label>
        <input type="text" name="first_name" class="form-control" value="<?= htmlspecialchars($u['first_name']) ?>" required></div>
      <div class="col-md-6"><label class="form-label">Last Name *</label>
        <input type="text" name="last_name" class="form-control" value="<?= htmlspecialchars($u['last_name']) ?>" required></div>
      <div class="col-md-6"><label class="form-label">Phone</label>
        <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($u['phone']) ?>"></div>
      <div class="col-md-6"><label class="form-label">New Password (leave blank to keep)</label>
        <input type="password" name="password" class="form-control"></div>
      <div class="col-md-6"><label class="form-label">Role *</label>
        <select name="role" class="form-select" required>
          <?php foreach (['admin','manager','collector','accountant','transport_officer','storekeeper'] as $r): ?>
            <option value="<?= $r ?>" <?= $r==$u['role']?'selected':'' ?>><?= ucwords(str_replace('_',' ',$r)) ?></option>
          <?php endforeach; ?>
        </select></div>
      <div class="col-md-6"><label class="form-label">Collection Center</label>
        <select name="collection_center_id" class="form-select">
          <option value="">-- None --</option>
          <?php foreach ($centers as $c): ?>
            <option value="<?= $c['id'] ?>" <?= $c['id']==$u['collection_center_id']?'selected':'' ?>><?= htmlspecialchars($c['name']) ?></option>
          <?php endforeach; ?>
        </select></div>
      <div class="col-md-6"><div class="form-check mt-4">
        <input class="form-check-input" type="checkbox" name="is_active" id="is_active" <?= $u['is_active']?'checked':'' ?>>
        <label class="form-check-label" for="is_active">Active</label>
      </div></div>
    </div>
    <div class="mt-4">
      <button class="btn btn-success"><i class="fas fa-save"></i> Update</button>
      <a href="index.php" class="btn btn-secondary">Cancel</a>
    </div>
  </form>
</div>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>