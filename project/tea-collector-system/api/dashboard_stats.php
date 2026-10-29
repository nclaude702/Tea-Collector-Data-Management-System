<?php
define('TCDMS', true);
require_once __DIR__ . '/../includes/functions.php';
header('Content-Type: application/json');

if (!isLoggedIn()) { http_response_code(401); echo json_encode(['error'=>'Unauthorized']); exit; }

$stats = [
    'farmers' => (int) dbFetchOne("SELECT COUNT(*) AS c FROM farmers WHERE is_active = 1")['c'],
    'today_collections' => (int) dbFetchOne("SELECT COUNT(*) AS c FROM tea_collections WHERE collection_date = CURDATE()")['c'],
    'today_weight' => (float) dbFetchOne("SELECT COALESCE(SUM(net_weight_kg),0) AS w FROM tea_collections WHERE collection_date = CURDATE()")['w'],
    'pending_payments' => (int) dbFetchOne("SELECT COUNT(*) AS c FROM payments WHERE payment_status='pending'")['c'],
];
echo json_encode($stats);