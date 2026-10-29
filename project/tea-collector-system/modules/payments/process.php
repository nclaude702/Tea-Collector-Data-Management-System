<?php
define('TCDMS', true);
require_once __DIR__ . '/../../includes/functions.php';
requireRole(['admin', 'accountant', 'manager']);

$id = (int)($_GET['id'] ?? 0);
if ($id) {
    dbQuery("UPDATE payments SET payment_status = 'paid', paid_at = NOW() WHERE id = :id", [':id' => $id]);
    logAudit('process', 'payments', $id);
    redirectWith('index.php', 'success', 'Payment marked as paid.');
}
redirectWith('index.php', 'error', 'Invalid request.');