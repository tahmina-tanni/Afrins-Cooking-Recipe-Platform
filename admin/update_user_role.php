<?php

require_once '../config/database.php';
require_once '../utils/functions.php';


// ==========================================
// ADMIN SECURITY
// ==========================================

if (!isLoggedIn()) {
    header('Location: ../index.html');
    exit;
}

if (!isAdmin()) {
    http_response_code(403);
    die('Access Denied: Admin only.');
}


// ==========================================
// ONLY POST REQUEST
// ==========================================

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: users.php');
    exit;
}


// ==========================================
// GET FORM DATA
// ==========================================

$userId = isset($_POST['user_id'])
    ? (int)$_POST['user_id']
    : 0;

$newRole = isset($_POST['role'])
    ? trim($_POST['role'])
    : '';


// ==========================================
// VALIDATION
// ==========================================

if ($userId <= 0) {

    header(
        'Location: users.php?error=' .
        urlencode('Invalid user.')
    );

    exit;
}


// Only these two roles are allowed

if (!in_array($newRole, ['user', 'admin'], true)) {

    header(
        'Location: users.php?error=' .
        urlencode('Invalid role.')
    );

    exit;
}


// ==========================================
// PROTECT CURRENT ADMIN
// ==========================================

$currentAdminId = (int)$_SESSION['user_id'];

if ($userId === $currentAdminId) {

    header(
        'Location: users.php?error=' .
        urlencode(
            'You cannot change your own admin role.'
        )
    );

    exit;
}


// ==========================================
// DATABASE
// ==========================================

$conn = connectDB();


// Check whether user exists

$stmt = $conn->prepare("
    SELECT id, name, role
    FROM users
    WHERE id = ?
");

$stmt->bind_param(
    "i",
    $userId
);

$stmt->execute();

$result = $stmt->get_result();


// User not found

if ($result->num_rows === 0) {

    $stmt->close();
    $conn->close();

    header(
        'Location: users.php?error=' .
        urlencode('User not found.')
    );

    exit;
}


$user = $result->fetch_assoc();

$stmt->close();


// ==========================================
// UPDATE ROLE
// ==========================================

$stmt = $conn->prepare("
    UPDATE users
    SET role = ?
    WHERE id = ?
");

$stmt->bind_param(
    "si",
    $newRole,
    $userId
);


if ($stmt->execute()) {

    $message =
        $user['name'] .
        ' is now ' .
        ucfirst($newRole) .
        '.';

    $stmt->close();
    $conn->close();

    header(
        'Location: users.php?success=' .
        urlencode($message)
    );

    exit;

}


// Update failed

$stmt->close();
$conn->close();

header(
    'Location: users.php?error=' .
    urlencode('Failed to update user role.')
);

exit;

?>