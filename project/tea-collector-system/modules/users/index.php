<?php
define('TCDMS', true);
$pageTitle = 'User Management';
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/sidebar.php';
requireRole('admin');

$users = dbFetchAll("SELECT * FROM users ORDER BY id DESC");
?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <h5 class="mb-0"><i class="fas fa-user-cog text-success"></i> System Users</h5>
  <a href="create.php" class="btn btn-success"><i class="fas fa-plus"></i> Add User</a>
</div>
<div class="table-card">
  <table class="table table-hover">
    <thead><tr><th>Username</th><th>Full Name</th><th>Email</th><th>Role</th><th>Status</th><th>Last Login</th><th>Actions</th></tr></thead>
    <tbody>
    <?php foreach ($users as $u): ?>
      <tr>
        <td><strong><?= htmlspecialchars($u['username']) ?></strong></td>
        <td><?= htmlspecialchars($u['first_name'].' '.$u['last_name']) ?></td>
        <td><?= htmlspecialchars($u['email']) ?></td>
        <td><span class="badge bg-primary"><?= htmlspecialchars($u['role']) ?></span></td>
        <td><?= $u['is_active'] ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">Inactive</span>' ?></td>
        <td><?= $u['last_login'] ? formatDate($u['last_login'],'d/m/Y H:i') : 'Never' ?></td>
        <td>
          <a href="edit.php?id=<?= $u['id'] ?>" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
          <?php if ($u['id'] != getCurrentUserId()): ?>
            <a href="delete.php?id=<?= $u['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Deactivate this user?')"><i class="fas fa-user-slash"></i></a>
          <?php endif; ?>
        </td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
</div>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>