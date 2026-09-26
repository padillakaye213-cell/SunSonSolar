<?php
header('Content-Type: application/json');
require 'db.php';

function authenticateUser($conn, $username, $password) {
    $stmt = $conn->prepare('SELECT user_id, firstname, lastname, role, password FROM users WHERE username = ? LIMIT 1');
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        return null;
    }

    $row = $result->fetch_assoc();

    if ($password !== $row['password']) {
        return null;
    }

    return $row;
}

function createUserSession($user, $username) {
    session_start();
    $_SESSION['user_id']  = $user['user_id'];
    $_SESSION['username'] = $username;
    $_SESSION['role']     = $user['role'];
    $_SESSION['name']     = $user['firstname'] . ' ' . $user['lastname'];

    return $_SESSION['name'];
}

$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

if ($username === '' || $password === '') {
    echo json_encode(['ok' => false, 'error' => 'Username and password are required.']);
    exit;
}

$user = authenticateUser($conn, $username, $password);

if ($user === null) {
    echo json_encode(['ok' => false, 'error' => 'Invalid username or password.']);
    exit;
}

$name = createUserSession($user, $username);

echo json_encode([
    'ok'   => true,
    'role' => $user['role'],
    'name' => $name
]);