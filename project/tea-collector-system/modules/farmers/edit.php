<?php
define('TCDMS', true);
$pageTitle = 'Edit Farmer';
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/sidebar.php';
requireRole(['admin', 'manager']);

$id = (int)($_GET['id'] ?? 0);
$farmer = dbFetchOne("SELECT * FROM farmers WHERE id = :id", [':id' => $id]);
if (!$farmer) { redirectWith('index.php', 'error', 'Farmer not found.'); }

$cooperatives = dbFetchAll("SELECT id, name FROM cooperatives WHERE is_active = 1");
$centers = dbFetchAll("SELECT id, name FROM collection_centers WHERE is_active = 1");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        ':national_id' => sanitize($_POST['national_id']),
        ':first_name'  => sanitize($_POST['first_name']),
        ':last_name'   => sanitize($_POST['last_name']),
        ':phone'       => sanitize($_POST['phone']),
        ':email'       => sanitize($_POST['email'] ?? ''),
        ':address'     => sanitize($_POST['address'] ?? ''),
        ':cooperative_id' => $_POST['cooperative_id'] ?: null,
        ':collection_center_id' => $_POST['collection_center_id'] ?: null,
        ':bank_account' => sanitize($_POST['bank_account'] ?? ''),
        ':mobile_money_number' => sanitize($_POST['mobile_money_number'] ?? ''),
        ':id' => $id
    ];

    try {
        dbQuery("UPDATE farmers SET national_id=:national_id, first_name=:first_name, last_name=:last_name,
                 phone=:phone, email=:email, address=:address, cooperative_id=:cooperative_id,
                 collection_center_id=:collection_center_id, bank_account=:bank_account,
                 mobile_money_number=:mobile_money_number WHERE id=:id", $data);
        logAudit('update', 'farmers', $id, $farmer, $data);
        redirectWith('index.php', 'success', 'Farmer updated successfully!');
    } catch (PDOException $e) {
        $error = 'Error: ' . $e->getMessage();
    }
}
?>
<div class="form-section">
  <h5><i class="fas fa-edit text-warning"></i> Edit Farmer: <?= htmlspecialchars($farmer['farmer_code']) ?></h5>
  <?php if (!empty($error)): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>
  <form method="POST">
    <div class="row g-3">
      <div class="col-md-6"><label class="form-label">National ID *</label>
        <input type="text" name="national_id" class="form-control" value="<?= htmlspecialchars($farmer['national_id']) ?>" required></div>
      <div class="col-md-6"><label class="form-label">Phone *</label>
        <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($farmer['phone']) ?>" required></div>
      <div class="col-md-6"><label class="form-label">First Name *</label>
        <input type="text" name="first_name" class="form-control" value="<?= htmlspecialchars($farmer['first_name']) ?>" required></div>
      <div class="col-md-6"><label class="form-label">Last Name *</label>
        <input type="text" name="last_name" class="form-control" value="<?= htmlspecialchars($farmer['last_name']) ?>" required></div>
      <div class="col-md-6"><label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($farmer['email']) ?>"></div>
      <div class="col-md-6"><label class="form-label">Address</label>
        <input type="text" name="address" class="form-control" value="<?= htmlspecialchars($farmer['address']) ?>"></div>
      <div class="col-md-6"><label class="form-label">Cooperative</label>
        <select name="cooperative_id" class="form-select">
          <option value="">-- Select --</option>
          <?php foreach ($cooperatives as $c): ?>
            <option value="<?= $c['id'] ?>" <?= $c['id']==$farmer['cooperative_id']?'selected':'' ?>><?= htmlspecialchars($c['name']) ?></option>
          <?php endforeach; ?>
        </select></div>
      <div class="col-md-6"><label class="form-label">Collection Center</label>
        <select name="collection_center_id" class="form-select">
          <option value="">-- Select --</option>
          <?php foreach ($centers as $c): ?>
            <option value="<?= $c['id'] ?>" <?= $c['id']==$farmer['collection_center_id']?'selected':'' ?>><?= htmlspecialchars($c['name']) ?></option>
          <?php endforeach; ?>
        </select></div>
      <div class="col-md-6"><label class="form-label">Bank Account</label>
        <input type="text" name="bank_account" class="form-control" value="<?= htmlspecialchars($farmer['bank_account']) ?>"></div>
      <div class="col-md-6"><label class="form-label">Mobile Money</label>
        <input type="text" name="mobile_money_number" class="form-control" value="<?= htmlspecialchars($farmer['mobile_money_number']) ?>"></div>
    </div>
    <div class="mt-4">
      <button class="btn btn-success"><i class="fas fa-save"></i> Update</button>
      <a href="index.php" class="btn btn-secondary">Cancel</a>
    </div>
  </form>
</div>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>