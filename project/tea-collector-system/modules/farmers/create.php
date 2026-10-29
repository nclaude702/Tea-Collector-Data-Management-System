<?php
define('TCDMS', true);
$pageTitle = 'Add New Farmer';
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/sidebar.php';
requireRole(['admin', 'manager']);

$cooperatives = dbFetchAll("SELECT id, name FROM cooperatives WHERE is_active = 1");
$centers = dbFetchAll("SELECT id, name FROM collection_centers WHERE is_active = 1");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        ':farmer_code' => generateCode('FRM', 'farmers', 'farmer_code'),
        ':national_id' => sanitize($_POST['national_id']),
        ':first_name'  => sanitize($_POST['first_name']),
        ':last_name'   => sanitize($_POST['last_name']),
        ':phone'       => sanitize($_POST['phone']),
        ':email'       => sanitize($_POST['email'] ?? ''),
        ':address'     => sanitize($_POST['address'] ?? ''),
        ':cooperative_id' => $_POST['cooperative_id'] ?: null,
        ':collection_center_id' => $_POST['collection_center_id'] ?: null,
        ':bank_account' => sanitize($_POST['bank_account'] ?? ''),
        ':mobile_money_number' => sanitize($_POST['mobile_money_number'] ?? '')
    ];

    try {
        $id = dbInsert("INSERT INTO farmers 
            (farmer_code, national_id, first_name, last_name, phone, email, address, cooperative_id, collection_center_id, bank_account, mobile_money_number)
            VALUES (:farmer_code,:national_id,:first_name,:last_name,:phone,:email,:address,:cooperative_id,:collection_center_id,:bank_account,:mobile_money_number)", $data);
        logAudit('create', 'farmers', $id, null, $data);
        redirectWith('index.php', 'success', 'Farmer added successfully!');
    } catch (PDOException $e) {
        $error = 'Error: ' . $e->getMessage();
    }
}
?>
<div class="form-section">
  <h5><i class="fas fa-user-plus text-success"></i> Add New Farmer</h5>
  <?php if (!empty($error)): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>
  <form method="POST">
    <div class="row g-3">
      <div class="col-md-6">
        <label class="form-label">National ID *</label>
        <input type="text" name="national_id" class="form-control" required>
      </div>
      <div class="col-md-6">
        <label class="form-label">Phone *</label>
        <input type="text" name="phone" class="form-control" placeholder="+2507XXXXXXXX" required>
      </div>
      <div class="col-md-6">
        <label class="form-label">First Name *</label>
        <input type="text" name="first_name" class="form-control" required>
      </div>
      <div class="col-md-6">
        <label class="form-label">Last Name *</label>
        <input type="text" name="last_name" class="form-control" required>
      </div>
      <div class="col-md-6">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control">
      </div>
      <div class="col-md-6">
        <label class="form-label">Address</label>
        <input type="text" name="address" class="form-control">
      </div>
      <div class="col-md-6">
        <label class="form-label">Cooperative</label>
        <select name="cooperative_id" class="form-select">
          <option value="">-- Select --</option>
          <?php foreach ($cooperatives as $c): ?>
            <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['name']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-6">
        <label class="form-label">Collection Center</label>
        <select name="collection_center_id" class="form-select">
          <option value="">-- Select --</option>
          <?php foreach ($centers as $c): ?>
            <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['name']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-6">
        <label class="form-label">Bank Account</label>
        <input type="text" name="bank_account" class="form-control">
      </div>
      <div class="col-md-6">
        <label class="form-label">Mobile Money Number</label>
        <input type="text" name="mobile_money_number" class="form-control">
      </div>
    </div>
    <div class="mt-4">
      <button class="btn btn-success"><i class="fas fa-save"></i> Save Farmer</button>
      <a href="index.php" class="btn btn-secondary">Cancel</a>
    </div>
  </form>
</div>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>