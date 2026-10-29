<?php
require_once __DIR__ . '/functions.php';
if (!isLoggedIn()) {
    redirectWith(SITE_URL . 'index.php', 'error', 'Please log in first.');
}