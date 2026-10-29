<?php

$pageTitle = 'Farmers Management';
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/sidebar.php';
require_once __DIR__ . '/../../includes/functions.php';
requireRole(['admin', 'manager']); // or whatever roles are needed

$search = sanitize($_GET['search'] ?? '');
$page   = max(1, (int)($_GET['page'] ?? 1));
$offset = ($page - 1) * RECORDS_PER_PAGE;

$where = "WHERE f.is_active = 1";
$params = [];
if (!empty($search)) {
    $where .= " AND (f.farmer_code LIKE :s OR f.first_name LIKE :s OR f.last_name LIKE :s OR f.phone LIKE :s OR f.national_id LIKE :s)";
    $params[':s'] = "%$search%";
}

$total = dbFetchOne("SELECT COUNT(*) AS c FROM farmers f $where", $params)['c'] ?? 0;
$farmers = dbFetchAll("
    SELECT f.*, c.name AS center_name, co.name AS coop_name
    FROM farmers f
    LEFT JOIN collection_centers c ON c.id = f.collection_center_id
    LEFT JOIN cooperatives co ON co.id = f.cooperative_id
    $where
    ORDER BY f.id DESC
    LIMIT " . RECORDS_PER_PAGE . " OFFSET $offset
", $params);
?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <h5 class="mb-0"><i class="fas fa-users text-success"></i> Farmers (<?= $total ?>)</h5>
  <div>
    <form class="d-inline" method="GET">
      <input type="text" name="search" class="form-control d-inline-block" style="width:250px" placeholder="Search..." value="<?= htmlspecialchars($search) ?>">
      <button class="btn btn-outline-success"><i class="fas fa-search"></i></button>
    </form>
    <a href="create.php" class="btn btn-success"><i class="fas fa-plus"></i> Add Farmer</a>
  </div>
</div>

<div class="table-card">
  <div class="table-responsive">
    <table class="table table-hover">
      <thead>
        <tr>
          <th>Code</th><th>Name</th><th>National ID</th><th>Phone</th>
          <th>Center</th><th>Cooperative</th><th>Actions</th>
        </tr>
      </thead>
      <tbody>
      <?php if (empty($farmers)): ?>
        <tr><td colspan="7" class="text-center text-muted py-4">No farmers found.</td></tr>
      <?php else: foreach ($farmers as $f): ?>
        <tr>
          <td><strong><?= htmlspecialchars($f['farmer_code']) ?></strong></td>
          <td><?= htmlspecialchars($f['first_name'] . ' ' . $f['last_name']) ?></td>
          <td><?= htmlspecialchars($f['national_id']) ?></td>
          <td><?= htmlspecialchars($f['phone']) ?></td>
          <td><?= htmlspecialchars($f['center_name'] ?? 'N/A') ?></td>
          <td><?= htmlspecialchars($f['coop_name'] ?? 'N/A') ?></td>
          <td>
            <a href="view.php?id=<?= $f['id'] ?>" class="btn btn-sm btn-info"><i class="fas fa-eye"></i></a>
            <a href="edit.php?id=<?= $f['id'] ?>" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
            <a href="delete.php?id=<?= $f['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this farmer?')"><i class="fas fa-trash"></i></a>
          </td>
        </tr>
      <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>
</div>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>