<?php
define('TCDMS', true);
require_once __DIR__ . '/../../includes/functions.php';
requireRole(['admin','manager']);

$id = (int)($_GET['id'] ?? 0);
if ($id) {
    $col = dbFetchOne("SELECT * FROM tea_collections WHERE id = :id", [':id' => $id]);
    if ($col) {
        dbQuery("UPDATE inventory SET current_stock_kg = current_stock_kg - :w WHERE collection_center_id = :c",
                [':w' => $col['net_weight_kg'], ':c' => $col['collection_center_id']]);
        dbQuery("DELETE FROM tea_collections WHERE id = :id", [':id' => $id]);
        logAudit('delete', 'tea_collections', $id, $col);
    }
    redirectWith('index.php', 'success', 'Collection deleted.');
}
redirectWith('index.php', 'error', 'Invalid request.');