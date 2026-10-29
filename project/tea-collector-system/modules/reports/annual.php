<?php
define('TCDMS', true);
$pageTitle = 'Annual Report';
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/sidebar.php';
requireRole(['admin', 'manager', 'accountant']);

$year = (int)($_GET['year'] ?? date('Y'));
$rows = dbFetchAll("
    SELECT MONTH(collection_date) AS m, COUNT(*) AS cnt, SUM(net_weight_kg) AS total_weight
    FROM tea_collections WHERE YEAR(collection_date) = :y
    GROUP BY MONTH(collection_date) ORDER BY m
", [':y' => $year]);
$grand = dbFetchOne("SELECT COUNT(*) AS cnt, COALESCE(SUM(net_weight_kg),0) AS w FROM tea_collections WHERE YEAR(collection_date)=:y", [':y'=>$year]);
?>
<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2 report-header-print">
  <div class="d-flex align-items-center gap-3">
    <img src="<?= SITE_URL ?>assets/images/sorwath-logo.svg" alt="SORWATHE Logo" style="height:52px; width:auto;">
    <h5 class="mb-0"><i class="fas fa-calendar text-success"></i> Annual Report - <?= $year ?></h5>
  </div>
  <div class="d-flex gap-2">
    <button onclick="window.print()" class="btn btn-outline-success"><i class="fas fa-print"></i> Print / PDF</button>
    <a href="javascript:window.print()" class="btn btn-success"><i class="fas fa-file-export"></i> Export</a>
  </div>
</div>
<div class="table-card mb-3">
  <form method="GET" class="row g-2 align-items-end">
    <div class="col-md-3"><label class="form-label">Year</label>
      <input type="number" name="year" class="form-control" value="<?= $year ?>"></div>
    <div class="col-md-2"><button class="btn btn-success w-100">Generate</button></div>
  </form>
</div>
<div class="table-card">
  <table class="table table-bordered">
    <thead><tr><th>Month</th><th>Collections</th><th>Total Weight (kg)</th></tr></thead>
    <tbody>
    <?php foreach ($rows as $r): ?>
      <tr>
        <td><?= date('F', mktime(0,0,0,$r['m'],1)) ?></td>
        <td><?= $r['cnt'] ?></td>
        <td><?= formatWeight($r['total_weight']) ?></td>
      </tr>
    <?php endforeach; ?>
    <tr class="table-success"><th>TOTAL</th><th><?= $grand['cnt'] ?></th><th><?= formatWeight($grand['w']) ?></th></tr>
    </tbody>
  </table>
</div>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>