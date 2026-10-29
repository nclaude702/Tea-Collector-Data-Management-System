<?php
define('TCDMS', true);
require_once __DIR__ . '/config/session.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';
if (isLoggedIn()) { logAudit('logout', 'users', getCurrentUserId()); }
session_unset(); session_destroy();
header('Location: index.php'); exit;