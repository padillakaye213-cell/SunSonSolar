<?php
header('Content-Type: application/json');
require 'db.php';

function getInput($key, $default = '') {
    return isset($_POST[$key]) ? trim($_POST[$key]) : $default;
}

function validateRegistration($data) {
    $errors = [];

    if ($data['firstname'] === '') $errors[] = 'First name is required.';
    if ($data['lastname'] === '') $errors[] = 'Last name is required.';
    if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) $errors[] = 'Invalid email address.';
    if (!preg_match('/^\S{4,}$/', $data['username'])) $errors[] = 'Username must be at least 4 characters, no spaces.';
    if (strlen($data['password']) < 8) $errors[] = 'Password must be at least 8 characters.';
    if ($data['password'] !== $data['confirmPassword']) $errors[] = 'Passwords do not match.';
    if ($data['accountType'] === 'employee' && empty($data['department'])) $errors[] = 'Department is required for employees.';

    return $errors;
}

function isDuplicateUser($conn, $username, $email) {
    $stmt = $conn->prepare('SELECT user_id FROM users WHERE username = ? OR email = ? LIMIT 1');
    $stmt->bind_param('ss', $username, $email);
    $stmt->execute();
    return $stmt->get_result()->num_rows > 0;
}

function insertUser($conn, $data) {
    $role = ($data['accountType'] === 'employee') ? 'employee' : 'customer';

    $stmt = $conn->prepare(
        'INSERT INTO users (role, firstname, middle_name, last_name, birthdate, gender, email, phone_number, address, department, username, password)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
    );
    $stmt->bind_param(
        'ssssssssssss',
        $role,
        $data['firstname'],
        $data['middle_name'],
        $data['lastname'],
        $data['birthdate'],
        $data['gender'],
        $data['email'],
        $data['phone'],
        $data['address'],
        $data['department'],
        $data['username'],
        $data['password']
    );

    return $stmt->execute();
}

$data = [
    'accountType'     => getInput('accountType', 'customer'),
    'department'      => getInput('department', null),
    'firstname'       => getInput('firstname'),
    'middle_name'     => getInput('middle_name') ?: null,
    'lastname'        => getInput('lastname'),
    'birthdate'       => getInput('birthdate', null),
    'gender'          => getInput('gender', null),
    'email'           => getInput('email'),
    'phone'           => getInput('phone'),
    'address'         => getInput('address'),
    'username'        => getInput('username'),
    'password'        => $_POST['password'] ?? '',
    'confirmPassword' => $_POST['confirmPassword'] ?? '',
];

$errors = validateRegistration($data);
if (!empty($errors)) {
    echo json_encode(['ok' => false, 'error' => implode(' ', $errors)]);
    exit;
}

if (isDuplicateUser($conn, $data['username'], $data['email'])) {
    echo json_encode(['ok' => false, 'error' => 'Username or email already taken.']);
    exit;
}

if (insertUser($conn, $data)) {
    echo json_encode(['ok' => true]);
} else {
    echo json_encode(['ok' => false, 'error' => 'Registration failed. Try again.']);
}