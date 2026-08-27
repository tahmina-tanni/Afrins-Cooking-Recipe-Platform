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


$conn = connectDB();



if ($_SERVER['REQUEST_METHOD'] == 'POST') {


    $name = trim($_POST['name']);


    $imagePath = null;



    // Image Upload

    if(isset($_FILES['image']) && $_FILES['image']['error'] == 0){


        $uploadDir = "../uploads/categories/";


        if(!is_dir($uploadDir)){
            mkdir($uploadDir,0777,true);
        }


        $fileName = time() . "_" . basename($_FILES['image']['name']);


        $targetPath = $uploadDir . $fileName;


        if(move_uploaded_file($_FILES['image']['tmp_name'],$targetPath)){


            $imagePath = "uploads/categories/" . $fileName;


        }

    }



    // Insert Data


    $stmt = $conn->prepare(
        "INSERT INTO categories (name,image) VALUES (?,?)"
    );


    $stmt->bind_param(
        "ss",
        $name,
        $imagePath
    );


    if($stmt->execute()){


        header(
            "Location: categories.php"
        );

        exit;


    }else{

        echo "Failed to add category";

    }



}



?>


<!DOCTYPE html>

<html>

<head>

<title>Add Category</title>


<link
href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css"
rel="stylesheet"
>


</head>



<body class="bg-gray-100">


<div class="max-w-xl mx-auto mt-10 bg-white p-8 rounded-lg shadow">


<h2 class="text-3xl font-bold mb-6">

Add New Category

</h2>



<form method="POST" enctype="multipart/form-data">



<label class="block mb-2 font-semibold">

Category Name

</label>


<input
type="text"
name="name"
required
class="w-full border p-3 rounded mb-5"
placeholder="Enter category name"
>



<label class="block mb-2 font-semibold">

Category Image

</label>


<input
type="file"
name="image"
class="w-full border p-3 rounded mb-6"
>



<button
type="submit"
class="bg-green-600 text-white px-6 py-3 rounded"
>

Add Category

</button>



<a
href="categories.php"
class="ml-3 text-gray-600"
>

Cancel

</a>



</form>



</div>


</body>

</html>