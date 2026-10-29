<?php
define('TCDMS', true);
require_once __DIR__ . '/../includes/functions.php';
header('Content-Type: application/json');

if (!isLoggedIn()) { http_response_code(401); echo json_encode(['error'=>'Unauthorized']); exit; }

$id = (int)($_GET['id'] ?? 0);
$farmer = dbFetchOne("SELECT id, farmer_code, first_name, last_name, phone FROM farmers WHERE id = :id AND is_active = 1", [':id'=>$id]);
echo json_encode($farmer ?: ['error'=>'Not found']);