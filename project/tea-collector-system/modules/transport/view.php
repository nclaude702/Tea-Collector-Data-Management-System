<?php
define('TCDMS', true);
$pageTitle = 'Transport Details';
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/sidebar.php';

$id = (int)($_GET['id'] ?? 0);
$s = dbFetchOne("
    SELECT ts.*, cc.name AS center_name, v.plate_number, v.vehicle_type,
           CONCAT(u.first_name,' ',u.last_name) AS driver_name
    FROM transport_schedules ts
    JOIN collection_centers cc ON cc.id = ts.collection_center_id
    LEFT JOIN vehicles v ON v.id = ts.vehicle_id
    LEFT JOIN users u ON u.id = ts.driver_id
    WHERE ts.id = :id", [':id' => $id]);
if (!$s) { redirectWith('index.php', 'error', 'Not found.'); }
?>
<div class="form-section">
  <h5><i class="fas fa-truck text-success"></i> Schedule: <?= htmlspecialchars($s['schedule_code']) ?></h5>
  <table class="table table-bordered">
    <tr><th style="width:30%">Collection Center</th><td><?= htmlspecialchars($s['center_name']) ?></td></tr>
    <tr><th>Vehicle</th><td><?= htmlspecialchars($s['plate_number'] ?? 'N/A').' '.($s['vehicle_type']?'('.$s['vehicle_type'].')':'') ?></td></tr>
    <tr><th>Driver</th><td><?= htmlspecialchars($s['driver_name'] ?? 'N/A') ?></td></tr>
    <tr><th>Scheduled</th><td><?= formatDate($s['scheduled_date']) ?> at <?= $s['scheduled_time'] ?></td></tr>
    <tr><th>Total Weight</th><td><?= formatWeight($s['total_weight_kg']) ?></td></tr>
    <tr><th>Status</th><td><?= getStatusBadge($s['status']) ?></td></tr>
    <tr><th>Departure</th><td><?= $s['departure_time'] ? formatDate($s['departure_time'],'d/m/Y H:i') : 'N/A' ?></td></tr>
    <tr><th>Arrival</th><td><?= $s['arrival_time'] ? formatDate($s['arrival_time'],'d/m/Y H:i') : 'N/A' ?></td></tr>
    <tr><th>Notes</th><td><?= nl2br(htmlspecialchars($s['notes'])) ?></td></tr>
  </table>
  <a href="edit.php?id=<?= $s['id'] ?>" class="btn btn-warning"><i class="fas fa-edit"></i> Edit</a>
  <a href="index.php" class="btn btn-secondary">Back</a>
</div>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>