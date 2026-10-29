<?php
define('TCDMS', true);
require_once __DIR__ . '/../../includes/functions.php';
requireLogin();

$id = (int)($_GET['id'] ?? 0);
$p = dbFetchOne("
    SELECT p.*, CONCAT(f.first_name,' ',f.last_name) AS farmer_name, f.farmer_code, f.phone, f.bank_account
    FROM payments p JOIN farmers f ON f.id = p.farmer_id WHERE p.id = :id", [':id' => $id]);
if (!$p) die('Payment not found.');

$items = dbFetchAll("
    SELECT tc.transaction_code, tc.collection_date, tc.net_weight_kg, tc.quality_grade
    FROM tea_collections tc
    WHERE tc.farmer_id = :fid AND tc.collection_date BETWEEN :s AND :e
    ORDER BY tc.collection_date
", [':fid' => $p['farmer_id'], ':s' => $p['period_start'], ':e' => $p['period_end']]);
?>
<!DOCTYPE html>
<html><head>
<meta charset="UTF-8"><title>Payment Statement</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>body{padding:30px;font-family:Arial,sans-serif;} .header{text-align:center;border-bottom:3px solid #1b4332;padding-bottom:15px;margin-bottom:25px;} .header h2{color:#1b4332;margin:0;} .header p{margin:0;color:#666;} table{width:100%;border-collapse:collapse;margin-top:15px;} th,td{border:1px solid #ddd;padding:8px;} th{background:#1b4332;color:#fff;} .total-row{background:#f0f8f0;font-weight:bold;} @media print{.no-print{display:none;}}</style>
</head><body>
<div class="header">
  <h2>SORWATHE Ltd.</h2>
  <p>Kinihira, Rulindo District, Rwanda</p>
  <p><strong>Payment Statement</strong></p>
</div>
<div class="row mb-3">
  <div class="col-md-6">
    <p><strong>Payment Code:</strong> <?= htmlspecialchars($p['payment_code']) ?></p>
    <p><strong>Farmer:</strong> <?= htmlspecialchars($p['farmer_name']) ?> (<?= htmlspecialchars($p['farmer_code']) ?>)</p>
    <p><strong>Phone:</strong> <?= htmlspecialchars($p['phone']) ?></p>
  </div>
  <div class="col-md-6 text-end">
    <p><strong>Period:</strong> <?= formatDate($p['period_start']) ?> - <?= formatDate($p['period_end']) ?></p>
    <p><strong>Status:</strong> <?= strtoupper($p['payment_status']) ?></p>
    <p><strong>Date Printed:</strong> <?= date('d/m/Y H:i') ?></p>
  </div>
</div>
<table>
  <thead><tr><th>#</th><th>Transaction</th><th>Date</th><th>Weight (kg)</th><th>Grade</th></tr></thead>
  <tbody>
    <?php $i=1; foreach ($items as $it): ?>
      <tr>
        <td><?= $i++ ?></td>
        <td><?= htmlspecialchars($it['transaction_code']) ?></td>
        <td><?= formatDate($it['collection_date']) ?></td>
        <td><?= number_format($it['net_weight_kg'],2) ?></td>
        <td><?= $it['quality_grade'] ?></td>
      </tr>
    <?php endforeach; ?>
    <tr class="total-row"><td colspan="3" class="text-end">TOTAL WEIGHT</td><td colspan="2"><?= formatWeight($p['total_weight_kg']) ?></td></tr>
    <tr class="total-row"><td colspan="3" class="text-end">TOTAL AMOUNT</td><td colspan="2"><?= formatCurrency($p['total_amount']) ?></td></tr>
    <tr class="total-row"><td colspan="3" class="text-end">NET AMOUNT</td><td colspan="2"><?= formatCurrency($p['net_amount']) ?></td></tr>
  </tbody>
</table>
<div class="mt-4 text-center no-print">
  <button onclick="window.print()" class="btn btn-success"><i class="fas fa-print"></i> Print</button>
  <a href="index.php" class="btn btn-secondary">Back</a>
</div>
</body></html>