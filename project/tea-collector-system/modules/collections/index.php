<?php
define('TCDMS', true);
$pageTitle = 'Tea Collections';
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/sidebar.php';
requireRole(['admin', 'manager', 'collector']);

$search = sanitize($_GET['search'] ?? '');
$from   = sanitize($_GET['from'] ?? date('Y-m-01'));
$to     = sanitize($_GET['to'] ?? date('Y-m-d'));

$sql = "SELECT tc.*, CONCAT(f.first_name,' ',f.last_name) AS farmer_name,
               u.first_name AS collector_name, cc.name AS center_name
        FROM tea_collections tc
        JOIN farmers f ON f.id = tc.farmer_id
        JOIN users u ON u.id = tc.collector_id
        JOIN collection_centers cc ON cc.id = tc.collection_center_id
        WHERE tc.collection_date BETWEEN :from AND :to";
$params = [':from' => $from, ':to' => $to];

if (!empty($search)) {
    $sql .= " AND (tc.transaction_code LIKE :s OR f.first_name LIKE :s OR f.last_name LIKE :s)";
    $params[':s'] = "%$search%";
}
$sql .= " ORDER BY tc.created_at DESC LIMIT 100";

$collections = dbFetchAll($sql, $params);
$totalWeight = 0;
foreach ($collections as $c) { $totalWeight += (float)$c['net_weight_kg']; }
?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <h5 class="mb-0"><i class="fas fa-boxes text-success"></i> Tea Collections</h5>
  <a href="create.php" class="btn btn-success"><i class="fas fa-plus"></i> Record Collection</a>
</div>

<div class="table-card mb-3">
  <form method="GET" class="row g-2 align-items-end">
    <div class="col-md-3"><label class="form-label">Search</label>
      <input type="text" name="search" class="form-control" value="<?= htmlspecialchars($search) ?>"></div>
    <div class="col-md-3"><label class="form-label">From</label>
      <input type="date" name="from" class="form-control" value="<?= $from ?>"></div>
    <div class="col-md-3"><label class="form-label">To</label>
      <input type="date" name="to" class="form-control" value="<?= $to ?>"></div>
    <div class="col-md-3"><button class="btn btn-success w-100"><i class="fas fa-filter"></i> Filter</button></div>
  </form>
</div>

<div class="stat-card green mb-3">
  <div><div class="stat-value"><?= formatWeight($totalWeight) ?></div><div class="stat-label">Total Weight (filtered)</div></div>
  <div class="stat-icon"><i class="fas fa-weight"></i></div>
</div>

<div class="table-card">
  <div class="table-responsive">
    <table class="table table-hover">
      <thead><tr>
        <th>Code</th><th>Date</th><th>Farmer</th><th>Center</th>
        <th>Weight</th><th>Grade</th><th>Status</th><th>Actions</th>
      </tr></thead>
      <tbody>
      <?php if (empty($collections)): ?>
        <tr><td colspan="8" class="text-center text-muted py-4">No collections found.</td></tr>
      <?php else: foreach ($collections as $c): ?>
        <tr>
          <td><strong><?= htmlspecialchars($c['transaction_code']) ?></strong></td>
          <td><?= formatDate($c['collection_date']) ?></td>
          <td><?= htmlspecialchars($c['farmer_name']) ?></td>
          <td><?= htmlspecialchars($c['center_name']) ?></td>
          <td><?= formatWeight($c['net_weight_kg']) ?></td>
          <td><?= getGradeBadge($c['quality_grade']) ?></td>
          <td><?= getStatusBadge($c['status']) ?></td>
          <td>
            <a href="view.php?id=<?= $c['id'] ?>" class="btn btn-sm btn-info"><i class="fas fa-eye"></i></a>
            <a href="edit.php?id=<?= $c['id'] ?>" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
            <a href="delete.php?id=<?= $c['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete?')"><i class="fas fa-trash"></i></a>
          </td>
        </tr>
      <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>
</div>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>