<?php
require_once __DIR__ . '/constants.php';

if (session_status() === PHP_SESSION_NONE) {
    session_name('TCDMS_SESSION');
    session_set_cookie_params(['httponly' => true, 'samesite' => 'Strict']);
    session_start();

    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > SESSION_TIMEOUT)) {
        session_unset(); session_destroy();
        header('Location: ' . SITE_URL . 'index.php?timeout=1'); exit;
    }
    $_SESSION['last_activity'] = time();
}

function isLoggedIn()          { return !empty($_SESSION['user_id']); }
function getCurrentUserId()    { return $_SESSION['user_id'] ?? null; }
function getCurrentUserRole()  { return $_SESSION['user_role'] ?? null; }
function getCurrentUser() {
    if (!isLoggedIn()) return null;
    return [
        'id' => $_SESSION['user_id'],
        'username' => $_SESSION['username'],
        'first_name' => $_SESSION['first_name'],
        'last_name'  => $_SESSION['last_name'],
        'role' => $_SESSION['user_role'],
        'collection_center_id' => $_SESSION['collection_center_id'] ?? null
    ];
}
function hasRole($roles) {
    if (!isLoggedIn()) return false;
    return is_array($roles) ? in_array(getCurrentUserRole(), $roles) : getCurrentUserRole() === $roles;
}
function requireLogin() {
    if (!isLoggedIn()) { header('Location: ' . SITE_URL . 'index.php?error=login_required'); exit; }
}
function requireRole($roles) {
    requireLogin();
    if (!hasRole($roles)) { header('Location: ' . SITE_URL . 'dashboard.php?error=access_denied'); exit; }
}