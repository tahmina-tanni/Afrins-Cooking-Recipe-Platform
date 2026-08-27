<?php
// api/auth.php - User authentication API

require_once '../config/database.php';

session_start();

// Handle API requests
$action = isset($_POST['action']) ? $_POST['action'] : '';

switch ($action) {

    case 'login':
        login();
        break;

    case 'register':
        register();
        break;

    case 'logout':
        logout();
        break;

    default:
        echo json_encode([
            'success' => false,
            'message' => 'Invalid action'
        ]);
        break;
}


// ==========================================
// LOGIN FUNCTION
// ==========================================

function login() {

    $conn = connectDB();

    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    // Validate input
    if (empty($email) || empty($password)) {

        echo json_encode([
            'success' => false,
            'message' => 'Email and password are required'
        ]);

        $conn->close();
        return;
    }

    // Prepare statement
    $stmt = $conn->prepare(
        "SELECT id, name, email, password, role
         FROM users
         WHERE email = ?"
    );

    if (!$stmt) {

        echo json_encode([
            'success' => false,
            'message' => 'Database query error'
        ]);

        $conn->close();
        return;
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();

    // User found
    if ($result->num_rows === 1) {

        $user = $result->fetch_assoc();

        // Verify password
        if (password_verify($password, $user['password'])) {

            // Store user information in session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_role'] = $user['role'];

            echo json_encode([
                'success' => true,
                'message' => 'Login successful',
                'user' => [
                    'id' => $user['id'],
                    'name' => $user['name'],
                    'email' => $user['email'],
                    'role' => $user['role']
                ]
            ]);

        } else {

            echo json_encode([
                'success' => false,
                'message' => 'Invalid password'
            ]);
        }

    } else {

        echo json_encode([
            'success' => false,
            'message' => 'User not found'
        ]);
    }

    $stmt->close();
    $conn->close();
}


// ==========================================
// REGISTER FUNCTION
// ==========================================

function register() {

    $conn = connectDB();

    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $confirm_password = isset($_POST['confirm_password'])
        ? $_POST['confirm_password']
        : '';

    // Validate input
    if (empty($name) || empty($email) || empty($password)) {

        echo json_encode([
            'success' => false,
            'message' => 'All fields are required'
        ]);

        $conn->close();
        return;
    }

    // Check password match
    if ($password !== $confirm_password) {

        echo json_encode([
            'success' => false,
            'message' => 'Passwords do not match'
        ]);

        $conn->close();
        return;
    }

    // Check if email already exists
    $stmt = $conn->prepare(
        "SELECT id FROM users WHERE email = ?"
    );

    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows > 0) {

        echo json_encode([
            'success' => false,
            'message' => 'Email already in use'
        ]);

        $stmt->close();
        $conn->close();
        return;
    }

    $stmt->close();

    // Hash password
    $hashed_password = password_hash(
        $password,
        PASSWORD_DEFAULT
    );

    // Insert new user
    // Role is NOT provided intentionally.
    // Database automatically sets role = 'user'.
    $stmt = $conn->prepare(
        "INSERT INTO users (name, email, password)
         VALUES (?, ?, ?)"
    );

    $stmt->bind_param(
        "sss",
        $name,
        $email,
        $hashed_password
    );

    if ($stmt->execute()) {

        echo json_encode([
            'success' => true,
            'message' => 'Registration successful'
        ]);

    } else {

        echo json_encode([
            'success' => false,
            'message' => 'Registration failed'
        ]);
    }

    $stmt->close();
    $conn->close();
}


// ==========================================
// LOGOUT FUNCTION
// ==========================================

function logout() {

    // Clear all session variables
    $_SESSION = [];

    // Destroy session
    session_destroy();

    echo json_encode([
        'success' => true,
        'message' => 'Logout successful'
    ]);
}

?>