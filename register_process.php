<?php
// register_process.php
header('Content-Type: application/json');
require 'db.php';

// Collect inputs
$accountType   = $_POST['accountType']   ?? 'customer';
$department    = $_POST['department']    ?? null;
$firstname     = trim($_POST['firstname'] ?? '');
$middle_name   = trim($_POST['middle_name'] ?? '') ?: null;
$lastname      = trim($_POST['lastname'] ?? '');
$birthdate     = $_POST['birthdate'] ?? null;
$gender        = $_POST['gender'] ?? null;
$email         = trim($_POST['email'] ?? '');
$phone         = trim($_POST['phone'] ?? '');
$address       = trim($_POST['address'] ?? '');
$username      = trim($_POST['username'] ?? '');
$password      = $_POST['password'] ?? '';
$confirm       = $_POST['confirmPassword'] ?? '';

// Server-side validation
$errors = [];

if ($firstname === '')  $errors[] = 'First name is required.';
if ($lastname === '')   $errors[] = 'Last name is required.';
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Invalid email address.';
if (!preg_match('/^\S{4,}$/', $username)) $errors[] = 'Username must be at least 4 characters, no spaces.';
if (strlen($password) < 8) $errors[] = 'Password must be at least 8 characters.';
if ($password !== $confirm) $errors[] = 'Passwords do not match.';
if ($accountType === 'employee' && empty($department)) $errors[] = 'Department is required for employees.';

if (!empty($errors)) {
    echo json_encode(['ok' => false, 'error' => implode(' ', $errors)]);
    exit;
}

// Check duplicates
$stmt = $conn->prepare('SELECT user_id FROM users WHERE username = ? OR email = ? LIMIT 1');
$stmt->bind_param('ss', $username, $email);
$stmt->execute();
if ($stmt->get_result()->num_rows > 0) {
    echo json_encode(['ok' => false, 'error' => 'Username or email already taken.']);
    exit;
}

// Insert
$role = ($accountType === 'employee') ? 'employee' : 'customer';

$stmt = $conn->prepare(
    'INSERT INTO users (role, firstname, middle_name, last_name, birthdate, gender, email, phone_number, address, department, username, password)
     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
);
$stmt->bind_param(
    'ssssssssssss',
    $role, $firstname, $middle_name, $lastname, $birthdate, $gender,
    $email, $phone, $address, $department, $username, $password
);

if ($stmt->execute()) {
    echo json_encode(['ok' => true]);
} else {
    echo json_encode(['ok' => false, 'error' => 'Registration failed. Try again.']);
}