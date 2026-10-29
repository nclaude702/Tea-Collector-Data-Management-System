<?php
define('TCDMS', true);
$pageTitle = 'Weekly Report';
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/sidebar.php';
requireRole(['admin', 'manager', 'accountant']);

$weekStart = sanitize($_GET['week'] ?? date('Y-m-d', strtotime('monday this week')));
$weekEnd = date('Y-m-d', strtotime($weekStart . ' +6 days'));

$rows = dbFetchAll("
    SELECT collection_date, COUNT(*) AS cnt, SUM(net_weight_kg) AS total_weight
    FROM tea_collections
    WHERE collection_date BETWEEN :s AND :e
    GROUP BY collection_date ORDER BY collection_date
", [':s' => $weekStart, ':e' => $weekEnd]);

$grand = 0;
foreach ($rows as $r) $grand += $r['total_weight'];
?>
<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2 report-header-print">
  <div class="d-flex align-items-center gap-3">
    <img src="<?= SITE_URL ?>assets/images/sorwath-logo.svg" alt="SORWATHE Logo" style="height:52px; width:auto;">
    <h5 class="mb-0"><i class="fas fa-calendar-week text-success"></i> Weekly Report</h5>
  </div>
  <div class="d-flex gap-2">
    <button onclick="window.print()" class="btn btn-outline-success"><i class="fas fa-print"></i> Print / PDF</button>
    <a href="javascript:window.print()" class="btn btn-success"><i class="fas fa-file-export"></i> Export</a>
  </div>
</div>
<div class="table-card mb-3">
  <form method="GET" class="row g-2 align-items-end">
    <div class="col-md-4"><label class="form-label">Week Starting</label>
      <input type="date" name="week" class="form-control" value="<?= $weekStart ?>"></div>
    <div class="col-md-2"><button class="btn btn-success w-100">Generate</button></div>
  </form>
</div>
<div class="table-card">
  <h6>Week: <?= formatDate($weekStart) ?> to <?= formatDate($weekEnd) ?></h6>
  <table class="table table-bordered">
    <thead><tr><th>Date</th><th>Collections</th><th>Total Weight (kg)</th></tr></thead>
    <tbody>
    <?php foreach ($rows as $r): ?>
      <tr>
        <td><?= formatDate($r['collection_date']) ?></td>
        <td><?= $r['cnt'] ?></td>
        <td><?= formatWeight($r['total_weight']) ?></td>
      </tr>
    <?php endforeach; ?>
    <tr class="table-success"><th colspan="2">TOTAL WEEKLY</th><th><?= formatWeight($grand) ?></th></tr>
    </tbody>
  </table>
</div>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>