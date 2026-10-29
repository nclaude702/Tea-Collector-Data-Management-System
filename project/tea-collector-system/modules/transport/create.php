<?php
define('TCDMS', true);
$pageTitle = 'New Transport Schedule';
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/sidebar.php';
requireRole(['admin', 'manager', 'transport_officer']);

$centers = dbFetchAll("SELECT id, name FROM collection_centers WHERE is_active = 1");
$vehicles = dbFetchAll("SELECT id, plate_number, vehicle_type, capacity_kg FROM vehicles WHERE is_available = 1");
$drivers = dbFetchAll("SELECT id, first_name, last_name FROM users WHERE role = 'transport_officer' AND is_active = 1");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        ':code' => generateCode('TRP', 'transport_schedules', 'schedule_code'),
        ':center' => (int)$_POST['collection_center_id'],
        ':vehicle' => $_POST['vehicle_id'] ?: null,
        ':driver' => $_POST['driver_id'] ?: null,
        ':date' => $_POST['scheduled_date'],
        ':time' => $_POST['scheduled_time'],
        ':weight' => (float)($_POST['total_weight_kg'] ?? 0),
        ':notes' => sanitize($_POST['notes'] ?? ''),
        ':uid' => getCurrentUserId()
    ];
    try {
        $id = dbInsert("INSERT INTO transport_schedules 
            (schedule_code, collection_center_id, vehicle_id, driver_id, scheduled_date, scheduled_time, total_weight_kg, notes, created_by)
            VALUES (:code,:center,:vehicle,:driver,:date,:time,:weight,:notes,:uid)", $data);
        logAudit('create', 'transport_schedules', $id);
        redirectWith('index.php', 'success', 'Transport schedule created!');
    } catch (PDOException $e) { $error = $e->getMessage(); }
}
?>
<div class="form-section">
  <h5><i class="fas fa-truck text-success"></i> New Transport Schedule</h5>
  <?php if (!empty($error)): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>
  <form method="POST">
    <div class="row g-3">
      <div class="col-md-6"><label class="form-label">Collection Center *</label>
        <select name="collection_center_id" class="form-select" required>
          <?php foreach ($centers as $c): ?>
            <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['name']) ?></option>
          <?php endforeach; ?>
        </select></div>
      <div class="col-md-6"><label class="form-label">Vehicle</label>
        <select name="vehicle_id" class="form-select">
          <option value="">-- Select --</option>
          <?php foreach ($vehicles as $v): ?>
            <option value="<?= $v['id'] ?>"><?= htmlspecialchars($v['plate_number'].' ('.$v['vehicle_type'].', '.$v['capacity_kg'].'kg)') ?></option>
          <?php endforeach; ?>
        </select></div>
      <div class="col-md-6"><label class="form-label">Driver</label>
        <select name="driver_id" class="form-select">
          <option value="">-- Select --</option>
          <?php foreach ($drivers as $d): ?>
            <option value="<?= $d['id'] ?>"><?= htmlspecialchars($d['first_name'].' '.$d['last_name']) ?></option>
          <?php endforeach; ?>
        </select></div>
      <div class="col-md-6"><label class="form-label">Total Weight (kg)</label>
        <input type="number" step="0.01" name="total_weight_kg" class="form-control"></div>
      <div class="col-md-6"><label class="form-label">Scheduled Date *</label>
        <input type="date" name="scheduled_date" class="form-control" value="<?= date('Y-m-d') ?>" required></div>
      <div class="col-md-6"><label class="form-label">Scheduled Time *</label>
        <input type="time" name="scheduled_time" class="form-control" value="<?= date('H:i') ?>" required></div>
      <div class="col-md-12"><label class="form-label">Notes</label>
        <textarea name="notes" class="form-control" rows="2"></textarea></div>
    </div>
    <div class="mt-4">
      <button class="btn btn-success"><i class="fas fa-save"></i> Save</button>
      <a href="index.php" class="btn btn-secondary">Cancel</a>
    </div>
  </form>
</div>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>