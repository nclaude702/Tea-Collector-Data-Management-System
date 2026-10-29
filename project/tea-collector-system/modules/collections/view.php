<?php
define('TCDMS', true);
$pageTitle = 'Collection Details';
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/sidebar.php';

$id = (int)($_GET['id'] ?? 0);
$col = dbFetchOne("
    SELECT tc.*, CONCAT(f.first_name,' ',f.last_name) AS farmer_name, f.farmer_code,
           u.first_name AS collector_first, u.last_name AS collector_last,
           cc.name AS center_name
    FROM tea_collections tc
    JOIN farmers f ON f.id = tc.farmer_id
    JOIN users u ON u.id = tc.collector_id
    JOIN collection_centers cc ON cc.id = tc.collection_center_id
    WHERE tc.id = :id", [':id' => $id]);
if (!$col) { redirectWith('index.php', 'error', 'Not found.'); }

$price = getCurrentPrice($col['quality_grade'], $col['collection_date']);
$amount = $price * $col['net_weight_kg'];
?>
<div class="form-section">
  <h5><i class="fas fa-receipt text-success"></i> Collection: <?= htmlspecialchars($col['transaction_code']) ?></h5>
  <table class="table table-bordered">
    <tr><th style="width:30%">Farmer</th><td><?= htmlspecialchars($col['farmer_name'].' ('.$col['farmer_code'].')') ?></td></tr>
    <tr><th>Collector</th><td><?= htmlspecialchars($col['collector_first'].' '.$col['collector_last']) ?></td></tr>
    <tr><th>Collection Center</th><td><?= htmlspecialchars($col['center_name']) ?></td></tr>
    <tr><th>Date & Time</th><td><?= formatDate($col['collection_date']) ?> <?= $col['collection_time'] ?></td></tr>
    <tr><th>Gross Weight</th><td><?= formatWeight($col['gross_weight_kg']) ?></td></tr>
    <tr><th>Net Weight</th><td><?= formatWeight($col['net_weight_kg']) ?></td></tr>
    <tr><th>Quality Grade</th><td><?= getGradeBadge($col['quality_grade']) ?></td></tr>
    <tr><th>Moisture Content</th><td><?= $col['moisture_content'] ?: 'N/A' ?></td></tr>
    <tr><th>Current Price</th><td><?= formatCurrency($price) ?> / kg</td></tr>
    <tr><th>Calculated Amount</th><td><strong class="text-success"><?= formatCurrency($amount) ?></strong></td></tr>
    <tr><th>Status</th><td><?= getStatusBadge($col['status']) ?></td></tr>
    <tr><th>Notes</th><td><?= nl2br(htmlspecialchars($col['notes'])) ?></td></tr>
  </table>
  <a href="edit.php?id=<?= $col['id'] ?>" class="btn btn-warning"><i class="fas fa-edit"></i> Edit</a>
  <a href="index.php" class="btn btn-secondary">Back</a>
</div>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>