<?php

define('DB_HOST',    'localhost');
define('DB_PORT',    3308);
define('DB_USER',    'root');
define('DB_PASS',    '');
define('DB_NAME',    'wpoets');
define('DB_CHARSET', 'utf8mb4');

$db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);

if ($db->connect_errno) {
    http_response_code(500);
    die(json_encode([
        'success' => false,
        'message' => 'Database connection failed: ' . $db->connect_error,
    ]));
}

$db->set_charset(DB_CHARSET);

$db->query("SET time_zone = '+00:00'");
