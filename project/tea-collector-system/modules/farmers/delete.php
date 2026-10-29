<?php
define('TCDMS', true);
require_once __DIR__ . '/../../includes/functions.php';
requireRole(['admin','manager']);

$id = (int)($_GET['id'] ?? 0);
if ($id) {
    dbQuery("UPDATE farmers SET is_active = 0 WHERE id = :id", [':id' => $id]);
    logAudit('delete', 'farmers', $id);
    redirectWith('index.php', 'success', 'Farmer deactivated successfully.');
}
redirectWith('index.php', 'error', 'Invalid request.');