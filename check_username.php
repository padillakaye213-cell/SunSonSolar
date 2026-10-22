<?php
require_once __DIR__ . '/includes/helpers.php';
header('Content-Type: application/json');
$u = trim($_GET['u'] ?? '');
$taken = $u !== '' && (bool)q('SELECT 1 FROM users WHERE username=?', [$u])->fetch();
echo json_encode(['taken' => $taken]);
