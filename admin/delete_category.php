<?php

require_once '../config/database.php';
require_once '../utils/functions.php';


if (!isLoggedIn()) {
    header("Location: ../index.html");
    exit;
}


if (!isAdmin()) {
    die("Access Denied");
}


if(isset($_GET['id'])){


    $id = intval($_GET['id']);


    $conn = connectDB();



    // Delete category

    $stmt = $conn->prepare(
        "DELETE FROM categories WHERE id=?"
    );


    $stmt->bind_param(
        "i",
        $id
    );


    $stmt->execute();


    $stmt->close();

    $conn->close();


}



header(
    "Location: categories.php"
);

exit;


?>