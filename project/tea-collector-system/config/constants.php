<?php
// Define constants only once
if (!defined('SITE_NAME')) {


    define('SITE_NAME', 'Tea Collector Data Management System');
    define('SITE_SHORT_NAME', 'TCDMS');
    define('COMPANY_NAME', 'SORWATHE Ltd.');
    define('SITE_URL', 'http://localhost/tea-collector-system/');
    define('ASSETS_URL', SITE_URL . 'assets/');
    define('SESSION_TIMEOUT', 1800);
    define('RECORDS_PER_PAGE', 20);
}

date_default_timezone_set('Africa/Kigali');