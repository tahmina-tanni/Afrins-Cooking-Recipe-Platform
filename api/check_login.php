<?php

require_once '../utils/functions.php';

header('Content-Type: application/json');

if (isLoggedIn()) {

    echo json_encode([
        'success' => true,
        'loggedIn' => true,
        'userId' => $_SESSION['user_id'],
        'userName' => $_SESSION['user_name'],
        'userEmail' => $_SESSION['user_email'] ?? '',
        'role' => $_SESSION['user_role'] ?? 'user'
    ]);

} else {

    echo json_encode([
        'success' => true,
        'loggedIn' => false
    ]);
}

?>