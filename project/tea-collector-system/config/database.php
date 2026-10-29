<?php
require_once __DIR__ . '/constants.php';

if (!defined('DB_HOST')) {
    define('DB_HOST', 'localhost');
    define('DB_USER', 'root');
    define('DB_PASS', '');
    define('DB_NAME', 'tea_collector_db');
    define('DB_CHARSET', 'utf8mb4');
}

function getDBConnection() {
    static $pdo = null;
    if ($pdo === null) {
        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        try {
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            error_log('DB Error: ' . $e->getMessage());
            die('Database connection failed. Check XAMPP MySQL service.');
        }
    }
    return $pdo;
}

function dbQuery($sql, $params = []) {
    $stmt = getDBConnection()->prepare($sql);
    $stmt->execute($params);
    return $stmt;
}
function dbFetchOne($sql, $params = []) { return dbQuery($sql, $params)->fetch(); }
function dbFetchAll($sql, $params = []) { return dbQuery($sql, $params)->fetchAll(); }
function dbInsert($sql, $params = [])  { dbQuery($sql, $params); return getDBConnection()->lastInsertId(); }