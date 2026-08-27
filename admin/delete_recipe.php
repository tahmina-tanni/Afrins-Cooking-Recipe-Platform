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


if ($recipeId <= 0) {

    header(
        'Location: recipes.php?error=' .
        urlencode('Invalid recipe.')
    );

    exit;
}


$conn = connectDB();


// Get image BEFORE deleting recipe

$stmt = $conn->prepare("
    SELECT image
    FROM recipes
    WHERE id = ?
");

$stmt->bind_param(
    "i",
    $recipeId
);

$stmt->execute();

$result = $stmt->get_result();


if ($result->num_rows === 0) {

    $stmt->close();
    $conn->close();

    header(
        'Location: recipes.php?error=' .
        urlencode('Recipe not found.')
    );

    exit;
}


$recipe = $result->fetch_assoc();

$imagePath = $recipe['image'] ?? '';

$stmt->close();


// Delete reviews first

$stmt = $conn->prepare("
    DELETE FROM reviews
    WHERE recipe_id = ?
");

$stmt->bind_param(
    "i",
    $recipeId
);

$stmt->execute();
$stmt->close();


// Delete recipe

$stmt = $conn->prepare("
    DELETE FROM recipes
    WHERE id = ?
");

$stmt->bind_param(
    "i",
    $recipeId
);

$stmt->execute();


if ($stmt->affected_rows > 0) {

    $stmt->close();
    $conn->close();


    // Delete image file

    if (!empty($imagePath)) {

        $fullImagePath =
            dirname(__DIR__) .
            DIRECTORY_SEPARATOR .
            str_replace(
                '/',
                DIRECTORY_SEPARATOR,
                $imagePath
            );


        if (is_file($fullImagePath)) {
            unlink($fullImagePath);
        }

    }


    header(
        'Location: recipes.php?success=' .
        urlencode('Recipe deleted successfully.')
    );

    exit;
}


$stmt->close();
$conn->close();


header(
    'Location: recipes.php?error=' .
    urlencode('Failed to delete recipe.')
);

exit;

?>