<?php
define('TCDMS', true);
$pageTitle = 'Dashboard';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/sidebar.php';

// Statistics
$totalFarmers    = dbFetchOne("SELECT COUNT(*) AS c FROM farmers WHERE is_active = 1")['c'] ?? 0;
$todayCollections= dbFetchOne("SELECT COUNT(*) AS c FROM tea_collections WHERE collection_date = CURDATE()")['c'] ?? 0;
$todayWeight     = dbFetchOne("SELECT COALESCE(SUM(net_weight_kg),0) AS w FROM tea_collections WHERE collection_date = CURDATE()")['w'] ?? 0;
$pendingPayments = dbFetchOne("SELECT COUNT(*) AS c FROM payments WHERE payment_status = 'pending'")['c'] ?? 0;
$totalPaid       = dbFetchOne("SELECT COALESCE(SUM(net_amount),0) AS a FROM payments WHERE payment_status = 'paid'")['a'] ?? 0;
$monthWeight     = dbFetchOne("SELECT COALESCE(SUM(net_weight_kg),0) AS w FROM tea_collections WHERE MONTH(collection_date)=MONTH(CURDATE()) AND YEAR(collection_date)=YEAR(CURDATE())")['w'] ?? 0;

// Recent collections
$recentCollections = dbFetchAll("
  SELECT tc.transaction_code, tc.collection_date, tc.net_weight_kg, tc.quality_grade,
         CONCAT(f.first_name,' ',f.last_name) AS farmer_name
  FROM tea_collections tc
  JOIN farmers f ON f.id = tc.farmer_id
  ORDER BY tc.created_at DESC LIMIT 10
");

// Weekly chart data (last 7 days)
$weekData = dbFetchAll("
  SELECT collection_date, COALESCE(SUM(net_weight_kg),0) AS total
  FROM tea_collections
  WHERE collection_date >= DATE_SUB(CURDATE(), INTERVAL 6 DAY)
  GROUP BY collection_date ORDER BY collection_date
");
$chartLabels = []; $chartValues = [];
foreach ($weekData as $row) {
    $chartLabels[] = date('D d/m', strtotime($row['collection_date']));
    $chartValues[] = (float)$row['total'];
}
?>
<div class="row">
  <div class="col-md-3">
    <div class="stat-card green">
      <div>
        <div class="stat-value"><?= $totalFarmers ?></div>
        <div class="stat-label">Active Farmers</div>
      </div>
      <div class="stat-icon"><i class="fas fa-users"></i></div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="stat-card blue">
      <div>
        <div class="stat-value"><?= $todayCollections ?></div>
        <div class="stat-label">Today's Collections</div>
      </div>
      <div class="stat-icon"><i class="fas fa-boxes"></i></div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="stat-card orange">
      <div>
        <div class="stat-value"><?= formatWeight($todayWeight) ?></div>
        <div class="stat-label">Today's Weight</div>
      </div>
      <div class="stat-icon"><i class="fas fa-weight"></i></div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="stat-card red">
      <div>
        <div class="stat-value"><?= $pendingPayments ?></div>
        <div class="stat-label">Pending Payments</div>
      </div>
      <div class="stat-icon"><i class="fas fa-money-bill-wave"></i></div>
    </div>
  </div>
</div>

<div class="row mt-3">
  <div class="col-md-8">
    <div class="table-card">
      <h5><i class="fas fa-chart-line"></i> Weekly Tea Collection (kg)</h5>
      <canvas id="weeklyChart" height="100"></canvas>
    </div>
  </div>
  <div class="col-md-4">
    <div class="table-card">
      <h5><i class="fas fa-info-circle"></i> Summary</h5>
      <ul class="list-group list-group-flush">
        <li class="list-group-item d-flex justify-content-between">
          <span>This Month Weight</span><strong><?= formatWeight($monthWeight) ?></strong>
        </li>
        <li class="list-group-item d-flex justify-content-between">
          <span>Total Paid Out</span><strong><?= formatCurrency($totalPaid) ?></strong>
        </li>
      </ul>
    </div>
  </div>
</div>

<div class="table-card mt-3">
  <h5><i class="fas fa-clock"></i> Recent Collections</h5>
  <div class="table-responsive">
    <table class="table table-hover">
      <thead>
        <tr>
          <th>Transaction</th><th>Farmer</th