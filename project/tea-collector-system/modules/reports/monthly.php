<?php
define('TCDMS', true);
$pageTitle = 'Monthly Report';
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/sidebar.php';
requireRole(['admin', 'manager', 'accountant']);

$month = (int)($_GET['month'] ?? date('n'));
$year = (int)($_GET['year'] ?? date('Y'));

$rows = dbFetchAll("
    SELECT quality_grade, COUNT(*) AS cnt, SUM(net_weight_kg) AS total_weight
    FROM tea_collections
    WHERE MONTH(collection_date) = :m AND YEAR(collection_date) = :y
    GROUP BY quality_grade
", [':m' => $month, ':y' => $year]);

$grand = dbFetchOne("SELECT COUNT(*) AS cnt, COALESCE(SUM(net_weight_kg),0) AS w FROM tea_collections WHERE MONTH(collection_date)=:m AND YEAR(collection_date)=:y", [':m'=>$month, ':y'=>$year]);
?>
<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2 report-header-print">
  <div class="d-flex align-items-center gap-3">
    <img src="<?= SITE_URL ?>assets/images/sorwath-logo.svg" alt="SORWATHE Logo" style="height:52px; width:auto;">
    <h5 class="mb-0"><i class="fas fa-calendar-alt text-success"></i> Monthly Report</h5>
  </div>
  <div class="d-flex gap-2">
    <button onclick="window.print()" class="btn btn-outline-success"><i class="fas fa-print"></i> Print / PDF</button>
    <a href="javascript:window.print()" class="btn btn-success"><i class="fas fa-file-export"></i> Export</a>
  </div>
</div>
<div class="table-card mb-3">
  <form method="GET" class="row g-2 align-items-end">
    <div class="col-md-3"><label class="form-label">Month</label>
      <select name="month" class="form-select">
        <?php for ($i=1;$i<=12;$i++): ?>
          <option value="<?= $i ?>" <?= $i==$month?'selected':'' ?>><?= date('F', mktime(0,0,0,$i,1)) ?></option>
        <?php endfor; ?>
      </select></div>
    <div class="col-md-3"><label class="form-label">Year</label>
      <input type="number" name="year" class="form-control" value="<?= $year ?>"></div>
    <div class="col-md-2"><button class="btn btn-success w-100">Generate</button></div>
  </form>
</div>
<div class="table-card">
  <h6>Month: <?= date('F Y', mktime(0,0,0,$month,1,$year)) ?></h6>
  <table class="table table-bordered">
    <thead><tr><th>Quality Grade</th><th>Collections</th><th>Total Weight (kg)</th></tr></thead>
    <tbody>
    <?php foreach ($rows as $r): ?>
      <tr>
        <td><?= getGradeBadge($r['quality_grade']) ?></td>
        <td><?= $r['cnt'] ?></td>
        <td><?= formatWeight($r['total_weight']) ?></td>
      </tr>
    <?php endforeach; ?>
    <tr class="table-success"><th>TOTAL</th><th><?= $grand['cnt'] ?></th><th><?= formatWeight($grand['w']) ?></th></tr>
    </tbody>
  </table>
</div>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>