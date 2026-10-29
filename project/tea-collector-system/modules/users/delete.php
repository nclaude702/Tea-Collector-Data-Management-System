<?php
define('TCDMS', true);
require_once __DIR__ . '/../../includes/functions.php';
requireRole('admin');

$id = (int)($_GET['id'] ?? 0);
if ($id && $id != getCurrentUserId()) {
    dbQuery("UPDATE users SET is_active = 0 WHERE id = :id", [':id' => $id]);
    logAudit('deactivate', 'users', $id);
    redirectWith('index.php', 'success', 'User deactivated.');
}
redirectWith('index.php', 'error', 'Invalid request.');