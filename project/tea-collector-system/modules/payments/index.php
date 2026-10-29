<?php
define('TCDMS', true);
$pageTitle = 'Payments';
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/sidebar.php';
requireRole(['admin', 'manager', 'accountant']);

$status = sanitize($_GET['status'] ?? '');
$sql = "SELECT p.*, CONCAT(f.first_name,' ',f.last_name) AS farmer_name, f.farmer_code
        FROM payments p JOIN farmers f ON f.id = p.farmer_id";
$params = [];
if ($status) { $sql .= " WHERE p.payment_status = :s"; $params[':s'] = $status; }
$sql .= " ORDER BY p.created_at DESC LIMIT 100";
$payments = dbFetchAll($sql, $params);
?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <h5 class="mb-0"><i class="fas fa-money-bill-wave text-success"></i> Payments</h5>
  <a href="calculate.php" class="btn btn-success"><i class="fas fa-calculator"></i> Calculate Payments</a>
</div>

<div class="table-card mb-3">
  <form method="GET" class="row g-2 align-items-end">
    <div class="col-md-4"><label class="form-label">Status</label>
      <select name="status" class="form-select">
        <option value="">All</option>
        <?php foreach (['pending','processing','paid','failed'] as $s): ?>
          <option value="<?= $s ?>" <?= $status==$s?'selected':'' ?>><?= ucfirst($s) ?></option>
        <?php endforeach; ?>
      </select></div>
    <div class="col-md-3"><button class="btn btn-success w-100">Filter</button></div>
  </form>
</div>

<div class="table-card">
  <div class="table-responsive">
    <table class="table table-hover">
      <thead><tr>
        <th>Code</th><th>Farmer</th><th>Period</th><th>Weight</th><th>Amount</th><th>Status</th><th>Actions</th>
      </tr></thead>
      <tbody>
      <?php if (empty($payments)): ?>
        <tr><td colspan="7" class="text-center text-muted py-4">No payments found.</td></tr>
      <?php else: foreach ($payments as $p): ?>
        <tr>
          <td><strong><?= htmlspecialchars($p['payment_code']) ?></strong></td>
          <td><?= htmlspecialchars($p['farmer_name']) ?></td>
          <td><?= formatDate($p['period_start']) ?> - <?= formatDate($p['period_end']) ?></td>
          <td><?= formatWeight($p['total_weight_kg']) ?></td>
          <td><strong><?= formatCurrency($p['net_amount']) ?></strong></td>
          <td><?= getStatusBadge($p['payment_status']) ?></td>
          <td>
            <a href="view.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-info"><i class="fas fa-eye"></i></a>
            <a href="statement.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-primary" target="_blank"><i class="fas fa-print"></i></a>
            <?php if ($p['payment_status']=='pending' && hasRole(['admin','accountant'])): ?>
              <a href="process.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-success"><i class="fas fa-check"></i></a>
            <?php endif; ?>
          </td>
        </tr>
      <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>
</div>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>