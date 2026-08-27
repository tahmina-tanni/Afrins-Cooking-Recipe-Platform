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

$result = $conn->query(
    "SELECT * FROM categories ORDER BY id DESC"
);


?>

<!DOCTYPE html>
<html>

<head>

<title>Categories - Admin Panel</title>

<link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">

</head>


<body class="bg-gray-100">


<div class="flex min-h-screen">


<!-- Sidebar -->

<div class="w-64 bg-green-800 text-white p-6">


<h1 class="text-2xl font-bold">
Afrin's Cooking
</h1>

<p class="mb-8">
Admin Panel
</p>


<a href="index.php"
class="block p-3 hover:bg-green-700 rounded">

Dashboard

</a>


<a href="recipes.php"
class="block p-3 hover:bg-green-700 rounded">

Recipes

</a>


<a href="users.php"
class="block p-3 hover:bg-green-700 rounded">

Users

</a>


<a href="categories.php"
class="block p-3 bg-green-700 rounded">

Categories

</a>


</div>



<!-- Content -->


<div class="flex-1 p-8">


<div class="flex justify-between mb-6">


<h2 class="text-3xl font-bold">
Categories Management
</h2>


<a href="add_category.php"
class="bg-green-600 text-white px-5 py-2 rounded">

+ Add Category

</a>


</div>



<div class="bg-white rounded-lg shadow p-6">


<table class="w-full">


<thead>

<tr class="border-b">

<th class="p-3 text-left">
ID
</th>

<th class="p-3 text-left">
Name
</th>

<th class="p-3 text-left">
Image
</th>

<th class="p-3">
Action
</th>


</tr>

</thead>



<tbody>


<?php while($row = $result->fetch_assoc()){ ?>


<tr class="border-b">


<td class="p-3">

<?php echo $row['id']; ?>

</td>


<td class="p-3">

<?php echo htmlspecialchars($row['name']); ?>

</td>


<td class="p-3">


<?php if($row['image']){ ?>

<img 
src="../<?php echo $row['image']; ?>"
width="60"
height="60"
class="rounded"
onerror="this.src='../uploads/recipes/icon.png.jpeg'"
>


<?php } else { ?>

No Image

<?php } ?>


</td>



<td class="p-3">


<a 
href="delete_category.php?id=<?php echo $row['id']; ?>"
onclick="return confirm('Delete this category?')"
class="bg-red-500 text-white px-3 py-1 rounded">

Delete

</a>


</td>


</tr>


<?php } ?>


</tbody>


</table>


</div>


</div>


</div>


</body>

</html>