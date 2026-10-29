<?php
define('TCDMS', true);
$pageTitle = 'Farmers Report';
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/sidebar.php';

$rows = dbFetchAll("
    SELECT f.farmer_code, CONCAT(f.first_name,' ',f.last_name) AS name,
           COUNT(tc.id) AS deliveries, COALESCE(SUM(tc.net_weight_kg),0) AS total_weight
    FROM farmers f
    LEFT JOIN tea_collections tc ON tc.farmer_id = f.id
    WHERE f.is_active = 1
    GROUP BY f.id ORDER BY total_weight DESC
");
?>
<div class="d-flex justify-content-between mb-3">
  <h5><i class="fas fa-users text-success"></i> Farmers Production Report</h5>
  <button onclick="window.print()" class="btn btn-outline-success"><i class="fas fa-print"></i> Print</button>
</div>
<div class="table-card">
  <table class="table table-hover">
    <thead><tr><th>#</th><th>Farmer Code</th><th>Name</th><th>Deliveries</th><th>Total Weight (kg)</th></tr></thead>
    <tbody>
    <?php $i=1; foreach ($rows as $r): ?>
      <tr>
        <td><?= $i++ ?></td>
        <td><?= htmlspecialchars($r['farmer_code']) ?></td>
        <td><?= htmlspecialchars($r['name']) ?></td>
        <td><?= $r['deliveries'] ?></td>
        <td><?= formatWeight($r['total_weight']) ?></td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
</div>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>