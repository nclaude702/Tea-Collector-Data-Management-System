<?php
define('TCDMS', true);
$pageTitle = 'Edit Transport Schedule';
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/sidebar.php';
requireRole(['admin', 'manager', 'transport_officer']);

$id = (int)($_GET['id'] ?? 0);
$s = dbFetchOne("SELECT * FROM transport_schedules WHERE id = :id", [':id' => $id]);
if (!$s) { redirectWith('index.php', 'error', 'Not found.'); }

$centers = dbFetchAll("SELECT id, name FROM collection_centers WHERE is_active = 1");
$vehicles = dbFetchAll("SELECT id, plate_number FROM vehicles");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        ':center' => (int)$_POST['collection_center_id'],
        ':vehicle' => $_POST['vehicle_id'] ?: null,
        ':date' => $_POST['scheduled_date'],
        ':time' => $_POST['scheduled_time'],
        ':weight' => (float)$_POST['total_weight_kg'],
        ':status' => $_POST['status'],
        ':notes' => sanitize($_POST['notes']),
        ':id' => $id
    ];
    dbQuery("UPDATE transport_schedules SET collection_center_id=:center, vehicle_id=:vehicle,
             scheduled_date=:date, scheduled_time=:time, total_weight_kg=:weight, status=:status,
             notes=:notes WHERE id=:id", $data);
    logAudit('update', 'transport_schedules', $id);
    redirectWith('index.php', 'success', 'Schedule updated!');
}
?>
<div class="form-section">
  <h5><i class="fas fa-edit text-warning"></i> Edit: <?= htmlspecialchars($s['schedule_code']) ?></h5>
  <form method="POST">
    <div class="row g-3">
      <div class="col-md-6"><label class="form-label">Collection Center *</label>
        <select name="collection_center_id" class="form-select" required>
          <?php foreach ($centers as $c): ?>
            <option value="<?= $c['id'] ?>" <?= $c['id']==$s['collection_center_id']?'selected':'' ?>><?= htmlspecialchars($c['name']) ?></option>
          <?php endforeach; ?>
        </select></div>
      <div class="col-md-6"><label class="form-label">Vehicle</label>
        <select name="vehicle_id" class="form-select">
          <option value="">-- None --</option>
          <?php foreach ($vehicles as $v): ?>
            <option value="<?= $v['id'] ?>" <?= $v['id']==$s['vehicle_id']?'selected':'' ?>><?= htmlspecialchars($v['plate_number']) ?></option>
          <?php endforeach; ?>
        </select></div>
      <div class="col-md-4"><label class="form-label">Date *</label>
        <input type="date" name="scheduled_date" class="form-control" value="<?= $s['scheduled_date'] ?>" required></div>
      <div class="col-md-4"><label class="form-label">Time *</label>
        <input type="time" name="scheduled_time" class="form-control" value="<?= $s['scheduled_time'] ?>" required></div>
      <div class="col-md-4"><label class="form-label">Weight (kg)</label>
        <input type="number" step="0.01" name="total_weight_kg" class="form-control" value="<?= $s['total_weight_kg'] ?>"></div>
      <div class="col-md-6"><label class="form-label">Status</label>
        <select name="status" class="form-select">
          <?php foreach (['scheduled','in_transit','delivered','cancelled'] as $st): ?>
            <option value="<?= $st ?>" <?= $st==$s['status']?'selected':'' ?>><?= ucfirst(str_replace('_',' ',$st)) ?></option>
          <?php endforeach; ?>
        </select></div>
      <div class="col-md-12"><label class="form-label">Notes</label>
        <textarea name="notes" class="form-control" rows="2"><?= htmlspecialchars($s['notes']) ?></textarea></div>
    </div>
    <div class="mt-4">
      <button class="btn btn-success"><i class="fas fa-save"></i> Update</button>
      <a href="index.php" class="btn btn-secondary">Cancel</a>
    </div>
  </form>
</div>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>