<?php
define('TCDMS', true);
$pageTitle = 'Inventory Management';
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/sidebar.php';
requireRole(['admin', 'manager', 'storekeeper']);

$inventories = dbFetchAll("
    SELECT i.*, cc.name AS center_name, cc.capacity_kg
    FROM inventory i JOIN collection_centers cc ON cc.id = i.collection_center_id
    ORDER BY cc.name
");
?>
<h5 class="mb-3"><i class="fas fa-warehouse text-success"></i> Inventory Levels</h5>
<div class="row">
<?php foreach ($inventories as $inv):
    $pct = $inv['capacity_kg'] > 0 ? ($inv['current_stock_kg'] / $inv['capacity_kg']) * 100 : 0;
    $pctClass = $pct > 80 ? 'bg-danger' : ($pct > 50 ? 'bg-warning' : 'bg-success');
?>
  <div class="col-md-6 mb-3">
    <div class="table-card">
      <h6><?= htmlspecialchars($inv['center_name']) ?></h6>
      <p class="mb-2">
        <strong><?= formatWeight($inv['current_stock_kg']) ?></strong>
        <span class="text-muted">/ <?= formatWeight($inv['capacity_kg']) ?></span>
      </p>
      <div class="progress mb-2" style="height: 20px;">
        <div class="progress-bar <?= $pctClass ?>" style="width: <?= min(100,$pct) ?>%"><?= round($pct,1) ?>%</div>
      </div>
      <small class="text-muted">Threshold: <?= formatWeight($inv['threshold_kg']) ?> | Last updated: <?= formatDate($inv['last_updated'],'d/m/Y H:i') ?></small>
    </div>
  </div>
<?php endforeach; ?>
</div>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>