<?php
define('TCDMS', true);
$pageTitle = 'Record Tea Collection';
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/sidebar.php';
requireRole(['admin', 'manager', 'collector']);

$farmers = dbFetchAll("SELECT id, farmer_code, first_name, last_name FROM farmers WHERE is_active = 1 ORDER BY first_name");
$centers = dbFetchAll("SELECT id, name FROM collection_centers WHERE is_active = 1");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $gross = (float)$_POST['gross_weight_kg'];
    $deduction = (float)($_POST['deduction'] ?? 0);
    $net = $gross - $deduction;

    $data = [
        ':transaction_code' => generateCode('TXN', 'tea_collections', 'transaction_code'),
        ':farmer_id' => (int)$_POST['farmer_id'],
        ':collector_id' => getCurrentUserId(),
        ':collection_center_id' => (int)$_POST['collection_center_id'],
        ':gross_weight_kg' => $gross,
        ':net_weight_kg' => $net,
        ':quality_grade' => $_POST['quality_grade'],
        ':moisture_content' => !empty($_POST['moisture_content']) ? (float)$_POST['moisture_content'] : null,
        ':collection_date' => $_POST['collection_date'],
        ':collection_time' => $_POST['collection_time'],
        ':notes' => sanitize($_POST['notes'] ?? '')
    ];

    try {
        $id = dbInsert("INSERT INTO tea_collections 
            (transaction_code, farmer_id, collector_id, collection_center_id, gross_weight_kg, net_weight_kg,
             quality_grade, moisture_content, collection_date, collection_time, notes)
            VALUES (:transaction_code,:farmer_id,:collector_id,:collection_center_id,:gross_weight_kg,:net_weight_kg,
                    :quality_grade,:moisture_content,:collection_date,:collection_time,:notes)", $data);

        // Update inventory
        dbQuery("UPDATE inventory SET current_stock_kg = current_stock_kg + :w WHERE collection_center_id = :c",
                [':w' => $net, ':c' => $data[':collection_center_id']]);

        logAudit('create', 'tea_collections', $id, null, $data);
        redirectWith('index.php', 'success', 'Collection recorded successfully!');
    } catch (PDOException $e) {
        $error = 'Error: ' . $e->getMessage();
    }
}
?>
<div class="form-section">
  <h5><i class="fas fa-plus-circle text-success"></i> Record New Tea Collection</h5>
  <?php if (!empty($error)): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>
  <form method="POST">
    <div class="row g-3">
      <div class="col-md-6"><label class="form-label">Farmer *</label>
        <select name="farmer_id" class="form-select" required>
          <option value="">-- Select Farmer --</option>
          <?php foreach ($farmers as $f): ?>
            <option value="<?= $f['id'] ?>"><?= htmlspecialchars($f['farmer_code'].' - '.$f['first_name'].' '.$f['last_name']) ?></option>
          <?php endforeach; ?>
        </select></div>
      <div class="col-md-6"><label class="form-label">Collection Center *</label>
        <select name="collection_center_id" class="form-select" required>
          <?php foreach ($centers as $c): ?>
            <option value="<?= $c['id'] ?>" <?= ($c['id']==getCurrentUser()['collection_center_id'])?'selected':'' ?>><?= htmlspecialchars($c['name']) ?></option>
          <?php endforeach; ?>
        </select></div>
      <div class="col-md-3"><label class="form-label">Gross Weight (kg) *</label>
        <input type="number" step="0.01" name="gross_weight_kg" id="gross" class="form-control" required></div>
      <div class="col-md-3"><label class="form-label">Deduction (kg)</label>
        <input type="number" step="0.01" name="deduction" id="deduction" class="form-control" value="0"></div>
      <div class="col-md-3"><label class="form-label">Net Weight (kg)</label>
        <input type="number" step="0.01" name="net_weight_kg" id="net" class="form-control" readonly></div>
      <div class="col-md-3"><label class="form-label">Quality Grade *</label>
        <select name="quality_grade" class="form-select" required>
          <option value="A">Grade A</option><option value="B">Grade B</option>
          <option value="C">Grade C</option><option value="D">Grade D</option>
        </select></div>
      <div class="col-md-3"><label class="form-label">Moisture Content (%)</label>
        <input type="number" step="0.01" name="moisture_content" class="form-control"></div>
      <div class="col-md-3"><label class="form-label">Collection Date *</label>
        <input type="date" name="collection_date" class="form-control" value="<?= date('Y-m-d') ?>" required></div>
      <div class="col-md-3"><label class="form-label">Collection Time *</label>
        <input type="time" name="collection_time" class="form-control" value="<?= date('H:i') ?>" required></div>
      <div class="col-md-12"><label class="form-label">Notes</label>
        <textarea name="notes" class="form-control" rows="2"></textarea></div>
    </div>
    <div class="mt-4">
      <button class="btn btn-success"><i class="fas fa-save"></i> Save Collection</button>
      <a href="index.php" class="btn btn-secondary">Cancel</a>
    </div>
  </form>
</div>
<script>
function calcNet() {
  const g = parseFloat(document.getElementById('gross').value) || 0;
  const d = parseFloat(document.getElementById('deduction').value) || 0;
  document.getElementById('net').value = (g - d).toFixed(2);
}
document.getElementById('gross').addEventListener('input', calcNet);
document.getElementById('deduction').addEventListener('input', calcNet);
</script>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>