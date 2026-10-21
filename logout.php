<?php
require_once __DIR__ . '/includes/helpers.php';
if (!empty($_SESSION['uid'])) audit(isset($_GET['timeout']) ? 'session_timeout' : 'logout');
$timeout = isset($_GET['timeout']);
$_SESSION = [];
if (ini_get('session.use_cookies')) { $p = session_get_cookie_params(); setcookie(session_name(), '', time() - 42000, $p['path'], $p['domain'], $p['secure'], $p['httponly']); }
session_destroy();
header('Location: ' . url('login.php') . ($timeout ? '?timeout=1' : ''));
exit;
