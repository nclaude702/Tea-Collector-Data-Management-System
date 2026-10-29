<?php
define('TCDMS', true);
$pageTitle = 'Calculate Payments';
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/sidebar.php';
requireRole(['admin', 'accountant', 'manager']);

$error = ''; $success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $periodStart = $_POST['period_start'];
    $periodEnd   = $_POST['period_end'];
    $method      = $_POST['payment_method'];

    // Get all farmers with unprocessed collections in the period
    $farmers = dbFetchAll("
        SELECT DISTINCT f.id, f.farmer_code, f.first_name, f.last_name
        FROM farmers f
        JOIN tea_collections tc ON tc.farmer_id = f.id
        WHERE tc.collection_date BETWEEN :s AND :e
          AND tc.status != 'processed'
    ", [':s' => $periodStart, ':e' => $periodEnd]);

    $count = 0;
    foreach ($farmers as $f) {
        $collections = dbFetchAll("
            SELECT * FROM tea_collections
            WHERE farmer_id = :fid AND collection_date BETWEEN :s AND :e AND status != 'processed'
        ", [':fid' => $f['id'], ':s' => $periodStart, ':e' => $periodEnd]);

        if (empty($collections)) continue;

        $totalWeight = 0; $totalAmount = 0;
        foreach ($collections as $c) {
            $price = getCurrentPrice($c['quality_grade'], $c['collection_date']);
            $totalWeight += $c['net_weight_kg'];
            $totalAmount += $c['net_weight_kg'] * $price;
        }

        $paymentCode = generateCode('PAY', 'payments', 'payment_code');
        $payId = dbInsert("INSERT INTO payments 
            (payment_code, farmer_id, period_start, period_end, total_weight_kg, total_amount,
             deductions, net_amount, payment_method, payment_status, processed_by)
            VALUES (:code,:fid,:ps,:pe,:tw,:ta,0,:na,:pm,'pending',:uid)", [
            ':code' => $paymentCode, ':fid' => $f['id'], ':ps' => $periodStart, ':pe' => $periodEnd,
            ':tw' => $totalWeight, ':ta' => $totalAmount, ':na' => $totalAmount,
            ':pm' => $method, ':uid' => getCurrentUserId()
        ]);

        // Mark collections as processed
        foreach ($collections as $c) {
            dbQuery("UPDATE tea_collections SET status = 'processed' WHERE id = :id", [':id' => $c['id']]);
        }
        logAudit('create', 'payments', $payId);
        $count++;
    }
    redirectWith('index.php', 'success', "$count payment record(s) generated successfully!");
}
?>
<div class="form-section">
  <h5><i class="fas fa-calculator text-success"></i> Calculate Payments for Period</h5>
  <form method="POST">
    <div class="row g-3">
      <div class="col-md-4"><label class="form-label">Period Start *</label>
        <input type="date" name="period_start" class="form-control" value="<?= date('Y-m-01') ?>" required></div>
      <div class="col-md-4"><label class="form-label">Period End *</label>
        <input type="date" name="period_end" class="form-control" value="<?= date('Y-m-t') ?>" required></div>
      <div class="col-md-4"><label class="form-label">Payment Method *</label>
        <select name="payment_method" class="form-select" required>
          <option value="bank_transfer">Bank Transfer</option>
          <option value="mobile_money">Mobile Money</option>
          <option value="cash">Cash</option>
        </select></div>
    </div>
    <div class="alert alert-info mt-3">
      <i class="fas fa-info-circle"></i> The system will automatically calculate payments based on tea weight, quality grade and current market prices for all unprocessed collections in the selected period.
    </div>
    <button class="btn btn-success"><i class="fas fa-calculator"></i> Calculate Payments</button>
    <a href="index.php" class="btn btn-secondary">Cancel</a>
  </form>
</div>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>