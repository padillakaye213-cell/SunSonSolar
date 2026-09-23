<?php
// signin_process.php
header('Content-Type: application/json');
require 'db.php';

$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

if ($username === '' || $password === '') {
    echo json_encode(['ok' => false, 'error' => 'Username and password are required.']);
    exit;
}

$stmt = $conn->prepare('SELECT user_id, firstname, lastname, role, password FROM users WHERE username = ? LIMIT 1');
$stmt->bind_param('s', $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['ok' => false, 'error' => 'Invalid username or password.']);
    exit;
}

$row = $result->fetch_assoc();

// ⚠️ Plain text compare for demo. Replace with password_verify() when hashing.
if ($password !== $row['password']) {
    echo json_encode(['ok' => false, 'error' => 'Invalid username or password.']);
    exit;
}

session_start();
$_SESSION['user_id'] = $row['user_id'];
$_SESSION['username'] = $username;
$_SESSION['role'] = $row['role'];
$_SESSION['name'] = $row['firstname'] . ' ' . $row['lastname'];

echo json_encode([
    'ok' => true,
    'role' => $row['role'],
    'name' => $_SESSION['name']
]);