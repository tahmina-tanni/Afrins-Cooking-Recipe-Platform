<?php

require_once '../config/database.php';
require_once '../utils/functions.php';


if (!isLoggedIn() || !isAdmin()) {
    http_response_code(403);
    die('Access Denied');
}


if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: recipes.php');
    exit;
}


$recipeId = isset($_POST['recipe_id'])
    ? (int)$_POST['recipe_id']
    : 0;

$featured = isset($_POST['featured'])
    ? (int)$_POST['featured']
    : -1;


if ($recipeId <= 0 ||
    !in_array($featured, [0, 1], true)) {

    header(
        'Location: recipes.php?error=' .
        urlencode('Invalid request.')
    );

    exit;
}


$conn = connectDB();


$stmt = $conn->prepare("
    UPDATE recipes
    SET featured = ?
    WHERE id = ?
");

$stmt->bind_param(
    "ii",
    $featured,
    $recipeId
);

$stmt->execute();


if ($stmt->affected_rows >= 0) {

    $message = $featured === 1
        ? 'Recipe featured successfully.'
        : 'Recipe removed from featured.';

    $stmt->close();
    $conn->close();

    header(
        'Location: recipes.php?success=' .
        urlencode($message)
    );

    exit;
}


$stmt->close();
$conn->close();


header(
    'Location: recipes.php?error=' .
    urlencode('Unable to update recipe.')
);

exit;

?>