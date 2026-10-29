<?php
define('TCDMS', true);
$pageTitle = 'Payment Details';
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/sidebar.php';

$id = (int)($_GET['id'] ?? 0);
$p = dbFetchOne("
    SELECT p.*, CONCAT(f.first_name,' ',f.last_name) AS farmer_name, f.farmer_code, f.phone
    FROM payments p JOIN farmers f ON f.id = p.farmer_id WHERE p.id = :id", [':id' => $id]);
if (!$p) { redirectWith('index.php', 'error', 'Payment not found.'); }
?>
<div class="form-section">
  <h5><i class="fas fa-receipt text-success"></i> Payment: <?= htmlspecialchars($p['payment_code']) ?></h5>
  <table class="table table-bordered">
    <tr><th style="width:30%">Farmer</th><td><?= htmlspecialchars($p['farmer_name'].' ('.$p['farmer_code'].')') ?></td></tr>
    <tr><th>Phone</th><td><?= htmlspecialchars($p['phone']) ?></td></tr>
    <tr><th>Period</th><td><?= formatDate($p['period_start']) ?> - <?= formatDate($p['period_end']) ?></td></tr>
    <tr><th>Total Weight</th><td><?= formatWeight($p['total_weight_kg']) ?></td></tr>
    <tr><th>Total Amount</th><td><?= formatCurrency($p['total_amount']) ?></td></tr>
    <tr><th>Deductions</th><td><?= formatCurrency($p['deductions']) ?></td></tr>
    <tr><th>Net Amount</th><td><strong class="text-success"><?= formatCurrency($p['net_amount']) ?></strong></td></tr>
    <tr><th>Method</th><td><?= ucwords(str_replace('_',' ',$p['payment_method'])) ?></td></tr>
    <tr><th>Status</th><td><?= getStatusBadge($p['payment_status']) ?></td></tr>
    <tr><th>Paid At</th><td><?= $p['paid_at'] ? formatDate($p['paid_at'], 'd/m/Y H:i') : 'N/A' ?></td></tr>
  </table>
  <a href="statement.php?id=<?= $p['id'] ?>" class="btn btn-primary" target="_blank"><i class="fas fa-print"></i> Print Statement</a>
  <a href="index.php" class="btn btn-secondary">Back</a>
</div>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>