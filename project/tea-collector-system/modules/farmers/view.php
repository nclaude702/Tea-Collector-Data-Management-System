<?php
define('TCDMS', true);
$pageTitle = 'Farmer Details';
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/sidebar.php';

$id = (int)($_GET['id'] ?? 0);
$farmer = dbFetchOne("
    SELECT f.*, c.name AS center_name, co.name AS coop_name
    FROM farmers f
    LEFT JOIN collection_centers c ON c.id = f.collection_center_id
    LEFT JOIN cooperatives co ON co.id = f.cooperative_id
    WHERE f.id = :id", [':id' => $id]);
if (!$farmer) { redirectWith('index.php', 'error', 'Farmer not found.'); }

$collections = dbFetchAll("SELECT * FROM tea_collections WHERE farmer_id = :id ORDER BY collection_date DESC LIMIT 10", [':id' => $id]);
$totalWeight = dbFetchOne("SELECT COALESCE(SUM(net_weight_kg),0) AS w FROM tea_collections WHERE farmer_id = :id", [':id' => $id])['w'];
$totalEarnings = dbFetchOne("SELECT COALESCE(SUM(net_amount),0) AS a FROM payments WHERE farmer_id = :id AND payment_status='paid'", [':id' => $id])['a'];
?>
<div class="row">
  <div class="col-md-5">
    <div class="form-section">
      <h5><i class="fas fa-user text-success"></i> Farmer Information</h5>
      <table class="table table-sm">
        <tr><th>Code:</th><td><?= htmlspecialchars($farmer['farmer_code']) ?></td></tr>
        <tr><th>Name:</th><td><?= htmlspecialchars($farmer['first_name'].' '.$farmer['last_name']) ?></td></tr>
        <tr><th>National ID:</th><td><?= htmlspecialchars($farmer['national_id']) ?></td></tr>
        <tr><th>Phone:</th><td><?= htmlspecialchars($farmer['phone']) ?></td></tr>
        <tr><th>Email:</th><td><?= htmlspecialchars($farmer['email']) ?></td></tr>
        <tr><th>Address:</th><td><?= htmlspecialchars($farmer['address']) ?></td></tr>
        <tr><th>Cooperative:</th><td><?= htmlspecialchars($farmer['coop_name'] ?? 'N/A') ?></td></tr>
        <tr><th>Center:</th><td><?= htmlspecialchars($farmer['center_name'] ?? 'N/A') ?></td></tr>
        <tr><th>Bank Account:</th><td><?= htmlspecialchars($farmer['bank_account']) ?></td></tr>
        <tr><th>Mobile Money:</th><td><?= htmlspecialchars($farmer['mobile_money_number']) ?></td></tr>
      </table>
      <a href="edit.php?id=<?= $farmer['id'] ?>" class="btn btn-warning"><i class="fas fa-edit"></i> Edit</a>
      <a href="index.php" class="btn btn-secondary">Back</a>
    </div>
  </div>
  <div class="col-md-7">
    <div class="row">
      <div class="col-6"><div class="stat-card green"><div><div class="stat-value"><?= formatWeight($totalWeight) ?></div><div class="stat-label">Total Delivered</div></div><div class="stat-icon"><i class="fas fa-weight"></i></div></div></div>
      <div class="col-6"><div class="stat-card blue"><div><div class="stat-value"><?= formatCurrency($totalEarnings) ?></div><div class="stat-label">Total Paid</div></div><div class="stat-icon"><i class="fas fa-money-bill"></i></div></div></div>
    </div>
    <div class="table-card">
      <h5>Recent Collections</h5>
      <table class="table table-sm">
        <thead><tr><th>Date</th><th>Weight</th><th>Grade</th><th>Status</th></tr></thead>
        <tbody>
          <?php foreach ($collections as $c): ?>
            <tr>
              <td><?= formatDate($c['collection_date']) ?></td>
              <td><?= formatWeight($c['net_weight_kg']) ?></td>
              <td><?= getGradeBadge($c['quality_grade']) ?></td>
              <td><?= getStatusBadge($c['status']) ?></td>
            </tr>
          <?php endforeach; ?>
          <?php if (empty($collections)): ?>
            <tr><td colspan="4" class="text-center text-muted">No collections yet.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>