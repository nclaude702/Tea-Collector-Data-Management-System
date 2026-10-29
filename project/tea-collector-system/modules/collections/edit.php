<?php
define('TCDMS', true);
$pageTitle = 'Edit Collection';
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/sidebar.php';
requireRole(['admin', 'manager', 'collector']);

$id = (int)($_GET['id'] ?? 0);
$col = dbFetchOne("SELECT * FROM tea_collections WHERE id = :id", [':id' => $id]);
if (!$col) { redirectWith('index.php', 'error', 'Collection not found.'); }

$farmers = dbFetchAll("SELECT id, farmer_code, first_name, last_name FROM farmers WHERE is_active = 1");
$centers = dbFetchAll("SELECT id, name FROM collection_centers WHERE is_active = 1");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $gross = (float)$_POST['gross_weight_kg'];
    $deduction = (float)($_POST['deduction'] ?? 0);
    $data = [
        ':farmer_id' => (int)$_POST['farmer_id'],
        ':collection_center_id' => (int)$_POST['collection_center_id'],
        ':gross_weight_kg' => $gross,
        ':net_weight_kg' => $gross - $deduction,
        ':quality_grade' => $_POST['quality_grade'],
        ':moisture_content' => !empty($_POST['moisture_content']) ? (float)$_POST['moisture_content'] : null,
        ':collection_date' => $_POST['collection_date'],
        ':collection_time' => $_POST['collection_time'],
        ':notes' => sanitize($_POST['notes'] ?? ''),
        ':id' => $id
    ];
    try {
        dbQuery("UPDATE tea_collections SET farmer_id=:farmer_id, collection_center_id=:collection_center_id,
                 gross_weight_kg=:gross_weight_kg, net_weight_kg=:net_weight_kg, quality_grade=:quality_grade,
                 moisture_content=:moisture_content, collection_date=:collection_date, collection_time=:collection_time,
                 notes=:notes WHERE id=:id", $data);
        logAudit('update', 'tea_collections', $id, $col, $data);
        redirectWith('index.php', 'success', 'Collection updated!');
    } catch (PDOException $e) { $error = $e->getMessage(); }
}
?>
<div class="form-section">
  <h5><i class="fas fa-edit text-warning"></i> Edit Collection: <?= htmlspecialchars($col['transaction_code']) ?></h5>
  <?php if (!empty($error)): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>
  <form method="POST">
    <div class="row g-3">
      <div class="col-md-6"><label class="form-label">Farmer *</label>
        <select name="farmer_id" class="form-select" required>
          <?php foreach ($farmers as $f): ?>
            <option value="<?= $f['id'] ?>" <?= $f['id']==$col['farmer_id']?'selected':'' ?>><?= htmlspecialchars($f['farmer_code'].' - '.$f['first_name'].' '.$f['last_name']) ?></option>
          <?php endforeach; ?>
        </select></div>
      <div class="col-md-6"><label class="form-label">Collection Center *</label>
        <select name="collection_center_id" class="form-select" required>
          <?php foreach ($centers as $c): ?>
            <option value="<?= $c['id'] ?>" <?= $c['id']==$col['collection_center_id']?'selected':'' ?>><?= htmlspecialchars($c['name']) ?></option>
          <?php endforeach; ?>
        </select></div>
      <div class="col-md-3"><label class="form-label">Gross Weight *</label>
        <input type="number" step="0.01" name="gross_weight_kg" class="form-control" value="<?= $col['gross_weight_kg'] ?>" required></div>
      <div class="col-md-3"><label class="form-label">Deduction</label>
        <input type="number" step="0.01" name="deduction" class="form-control" value="<?= $col['gross_weight_kg'] - $col['net_weight_kg'] ?>"></div>
      <div class="col-md-3"><label class="form-label">Net Weight</label>
        <input type="number" step="0.01" name="net_weight_kg" class="form-control" value="<?= $col['net_weight_kg'] ?>" readonly></div>
      <div class="col-md-3"><label class="form-label">Quality Grade *</label>
        <select name="quality_grade" class="form-select">
          <?php foreach (['A','B','C','D'] as $g): ?>
            <option value="<?= $g ?>" <?= $g==$col['quality_grade']?'selected':'' ?>>Grade <?= $g ?></option>
          <?php endforeach; ?>
        </select></div>
      <div class="col-md-3"><label class="form-label">Moisture Content</label>
        <input type="number" step="0.01" name="moisture_content" class="form-control" value="<?= $col['moisture_content'] ?>"></div>
      <div class="col-md-3"><label class="form-label">Date *</label>
        <input type="date" name="collection_date" class="form-control" value="<?= $col['collection_date'] ?>" required></div>
      <div class="col-md-3"><label class="form-label">Time *</label>
        <input type="time" name="collection_time" class="form-control" value="<?= $col['collection_time'] ?>" required></div>
      <div class="col-md-12"><label class="form-label">Notes</label>
        <textarea name="notes" class="form-control" rows="2"><?= htmlspecialchars($col['notes']) ?></textarea></div>
    </div>
    <div class="mt-4">
      <button class="btn btn-success"><i class="fas fa-save"></i> Update</button>
      <a href="index.php" class="btn btn-secondary">Cancel</a>
    </div>
  </form>
</div>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>