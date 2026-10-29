<?php
define('TCDMS', true);
$pageTitle = 'Daily Report';
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/sidebar.php';
requireRole(['admin', 'manager', 'accountant']);

$date = sanitize($_GET['date'] ?? date('Y-m-d'));
$rows = dbFetchAll("
    SELECT tc.quality_grade, COUNT(*) AS cnt, SUM(tc.net_weight_kg) AS total_weight
    FROM tea_collections tc
    WHERE tc.collection_date = :d
    GROUP BY tc.quality_grade
", [':d' => $date]);
$grand = dbFetchOne("SELECT COUNT(*) AS cnt, COALESCE(SUM(net_weight_kg),0) AS w FROM tea_collections WHERE collection_date = :d", [':d' => $date]);
?>
<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2 report-header-print">
  <div class="d-flex align-items-center gap-3">
    <img src="<?= SITE_URL ?>assets/images/sorwath-logo.svg" alt="SORWATHE Logo" style="height:52px; width:auto;">
    <h5 class="mb-0"><i class="fas fa-calendar-day text-success"></i> Daily Collection Report</h5>
  </div>
  <div class="d-flex gap-2">
    <button onclick="window.print()" class="btn btn-outline-success"><i class="fas fa-print"></i> Print / PDF</button>
    <a href="javascript:window.print()" class="btn btn-success"><i class="fas fa-file-export"></i> Export</a>
  </div>
</div>
<div class="table-card mb-3">
  <form method="GET" class="row g-2 align-items-end">
    <div class="col-md-4"><label class="form-label">Date</label>
      <input type="date" name="date" class="form-control" value="<?= $date ?>"></div>
    <div class="col-md-2"><button class="btn btn-success w-100">Generate</button></div>
  </form>
</div>
<div class="table-card">
  <h6>Report for <?= formatDate($date) ?></h6>
  <table class="table table-bordered">
    <thead><tr><th>Quality Grade</th><th>Number of Collections</th><th>Total Weight (kg)</th></tr></thead>
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