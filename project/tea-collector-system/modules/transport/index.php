<?php
define('TCDMS', true);
$pageTitle = 'Transport Schedules';
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/sidebar.php';
requireRole(['admin', 'manager', 'transport_officer']);

$schedules = dbFetchAll("
    SELECT ts.*, cc.name AS center_name, v.plate_number
    FROM transport_schedules ts
    JOIN collection_centers cc ON cc.id = ts.collection_center_id
    LEFT JOIN vehicles v ON v.id = ts.vehicle_id
    ORDER BY ts.scheduled_date DESC LIMIT 100
");
?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <h5 class="mb-0"><i class="fas fa-truck text-success"></i> Transport Schedules</h5>
  <a href="create.php" class="btn btn-success"><i class="fas fa-plus"></i> New Schedule</a>
</div>
<div class="table-card">
  <div class="table-responsive">
    <table class="table table-hover">
      <thead><tr>
        <th>Code</th><th>Date</th><th>Time</th><th>Center</th><th>Vehicle</th><th>Weight</th><th>Status</th><th>Actions</th>
      </tr></thead>
      <tbody>
      <?php if (empty($schedules)): ?>
        <tr><td colspan="8" class="text-center text-muted py-4">No schedules found.</td></tr>
      <?php else: foreach ($schedules as $s): ?>
        <tr>
          <td><strong><?= htmlspecialchars($s['schedule_code']) ?></strong></td>
          <td><?= formatDate($s['scheduled_date']) ?></td>
          <td><?= $s['scheduled_time'] ?></td>
          <td><?= htmlspecialchars($s['center_name']) ?></td>
          <td><?= htmlspecialchars($s['plate_number'] ?? 'N/A') ?></td>
          <td><?= formatWeight($s['total_weight_kg']) ?></td>
          <td><?= getStatusBadge($s['status']) ?></td>
          <td>
            <a href="view.php?id=<?= $s['id'] ?>" class="btn btn-sm btn-info"><i class="fas fa-eye"></i></a>
            <a href="edit.php?id=<?= $s['id'] ?>" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
          </td>
        </tr>
      <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>
</div>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>