<?php
// db.php
$DB_HOST = '127.0.0.1';
$DB_USER = 'root';        // change if needed
$DB_PASS = '';            // change if needed
$DB_NAME = 'sunsonsolar';

$conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
if ($conn->connect_error) {
    http_response_code(500);
    die(json_encode(['ok' => false, 'error' => 'DB connection failed']));
}
$conn->set_charset('utf8mb4');