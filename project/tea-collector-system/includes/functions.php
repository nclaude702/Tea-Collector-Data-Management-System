<?php
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/session.php';

function sanitize($data) {
    if (is_array($data)) return array_map('sanitize', $data);
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}
function generateCode($prefix, $table, $column) {
    $date = date('Ymd');
    $sql = "SELECT COUNT(*) AS c FROM $table WHERE $column LIKE :p";
    $row = dbFetchOne($sql, [':p' => "$prefix-$date%"]);
    $n = ($row['c'] ?? 0) + 1;
    return $prefix . '-' . $date . '-' . str_pad($n, 4, '0', STR_PAD_LEFT);
}
function formatCurrency($amount) { return number_format((float)$amount, 2) . ' RWF'; }
function formatWeight($kg) { return number_format((float)$kg, 2) . ' kg'; }
function formatDate($d, $f = 'd/m/Y') { return $d ? date($f, strtotime($d)) : 'N/A'; }
function getGradeBadge($g) {
    $c = ['A'=>'success','B'=>'primary','C'=>'warning','D'=>'danger'][$g] ?? 'secondary';
    return "<span class='badge bg-$c'>Grade $g</span>";
}
function getStatusBadge($s) {
    $m = ['pending'=>['warning','Pending'],'verified'=>['info','Verified'],'processed'=>['success','Processed'],
          'paid'=>['success','Paid'],'processing'=>['info','Processing'],'failed'=>['danger','Failed'],
          'scheduled'=>['primary','Scheduled'],'in_transit'=>['warning','In Transit'],
          'delivered'=>['success','Delivered'],'cancelled'=>['danger','Cancelled']];
    [$c,$t] = $m[$s] ?? ['secondary', ucfirst($s)];
    return "<span class='badge bg-$c'>$t</span>";
}
function getCurrentPrice($grade, $date = null) {
    $date = $date ?? date('Y-m-d');
    $sql = "SELECT price_per_kg FROM price_configurations
            WHERE quality_grade = :g AND effective_from <= :date_from
              AND (effective_to IS NULL OR effective_to >= :date_to)
            ORDER BY effective_from DESC LIMIT 1";
    $r = dbFetchOne($sql, [':g' => $grade, ':date_from' => $date, ':date_to' => $date]);
    return $r ? (float)$r['price_per_kg'] : 0.0;
}
function logAudit($action, $entityType, $entityId = null, $old = null, $new = null) {
    $sql = "INSERT INTO audit_logs (user_id, action, entity_type, entity_id, old_values, new_values, ip_address)
            VALUES (:u,:a,:e,:i,:o,:n,:ip)";
    dbQuery($sql, [
        ':u' => getCurrentUserId(), ':a' => $action, ':e' => $entityType, ':i' => $entityId,
        ':o' => $old ? json_encode($old) : null, ':n' => $new ? json_encode($new) : null,
        ':ip' => $_SERVER['REMOTE_ADDR'] ?? null
    ]);
}
function redirectWith($url, $type, $msg) {
    $_SESSION['flash'] = ['type' => $type, 'message' => $msg];
    header("Location: $url"); exit;
}
function displayFlash() {
    if (!empty($_SESSION['flash'])) {
        $f = $_SESSION['flash']; unset($_SESSION['flash']);
        $cls = ['success'=>'alert-success','error'=>'alert-danger','warning'=>'alert-warning','info'=>'alert-info'][$f['type']] ?? 'alert-info';
        echo "<div class='alert $cls alert-dismissible fade show'>" . htmlspecialchars($f['message']) .
             "<button type='button' class='btn-close' data-bs-dismiss='alert'></button></div>";
    }
}