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


$adminName = htmlspecialchars(
    $_SESSION['user_name'] ?? 'Admin'
);


// ==========================================
// DATABASE
// ==========================================

$conn = connectDB();

$result = $conn->query("
    SELECT
        r.id,
        r.title,
        r.description,
        r.image,
        r.featured,
        r.created_at,
        u.name AS author,
        u.email AS author_email,
        c.name AS category
    FROM recipes r
    LEFT JOIN users u
        ON r.user_id = u.id
    LEFT JOIN categories c
        ON r.category_id = c.id
    ORDER BY r.created_at DESC
");

$recipes = [];

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $recipes[] = $row;
    }
}

$totalRecipes = count($recipes);

$conn->close();

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Recipe Management - Afrin's Cooking</title>

    <link
        href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css"
        rel="stylesheet"
    >

    <link
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"
        rel="stylesheet"
    >

</head>


<body class="bg-gray-100">

<div class="flex min-h-screen">


    <!-- SIDEBAR -->

    <aside class="w-64 bg-green-800 text-white p-6">

        <h1 class="text-2xl font-bold mb-2">
            Afrin's Cooking
        </h1>

        <p class="text-green-200 mb-10">
            Admin Panel
        </p>


        <nav class="space-y-3">

            <a
                href="index.php"
                class="block px-4 py-3 hover:bg-green-700 rounded-lg"
            >
                <i class="fas fa-chart-line mr-2"></i>
                Dashboard
            </a>


            <a
                href="recipes.php"
                class="block px-4 py-3 bg-green-700 rounded-lg"
            >
                <i class="fas fa-utensils mr-2"></i>
                Recipes
            </a>


            <a
                href="users.php"
                class="block px-4 py-3 hover:bg-green-700 rounded-lg"
            >
                <i class="fas fa-users mr-2"></i>
                Users
            </a>


            <a
                href="#"
                class="block px-4 py-3 hover:bg-green-700 rounded-lg"
            >
                <i class="fas fa-list mr-2"></i>
                Categories
            </a>


            <a
                href="../index.html"
                class="block px-4 py-3 hover:bg-green-700 rounded-lg mt-6"
            >
                <i class="fas fa-home mr-2"></i>
                Back to Website
            </a>

        </nav>

    </aside>



    <!-- MAIN CONTENT -->

    <main class="flex-1">


        <header
            class="bg-white shadow-sm px-8 py-5 flex justify-between items-center"
        >

            <div>

                <h2 class="text-2xl font-bold text-gray-800">
                    Recipe Management
                </h2>

                <p class="text-gray-500 mt-1">
                    Manage all recipes on the platform
                </p>

            </div>


            <div class="text-right">

                <p class="font-semibold text-gray-800">
                    <?php echo $adminName; ?>
                </p>

                <p class="text-xs text-gray-500">
                    Administrator
                </p>

            </div>

        </header>



        <div class="p-8">


            <?php if (isset($_GET['success'])): ?>

                <div
                    class="mb-6 bg-green-100 border border-green-300 text-green-800 px-5 py-4 rounded-lg"
                >
                    <i class="fas fa-check-circle mr-2"></i>

                    <?php
                    echo htmlspecialchars($_GET['success']);
                    ?>
                </div>

            <?php endif; ?>


            <?php if (isset($_GET['error'])): ?>

                <div
                    class="mb-6 bg-red-100 border border-red-300 text-red-800 px-5 py-4 rounded-lg"
                >
                    <i class="fas fa-exclamation-circle mr-2"></i>

                    <?php
                    echo htmlspecialchars($_GET['error']);
                    ?>
                </div>

            <?php endif; ?>



            <div
                class="flex justify-between items-center mb-6"
            >

                <div>

                    <h3 class="text-xl font-semibold text-gray-800">
                        All Recipes
                    </h3>

                    <p class="text-gray-500 text-sm mt-1">
                        Feature, unfeature or remove recipes
                    </p>

                </div>


                <div
                    class="bg-green-100 text-green-800 px-4 py-2 rounded-lg font-semibold"
                >
                    <i class="fas fa-utensils mr-2"></i>

                    Total Recipes:
                    <?php echo $totalRecipes; ?>
                </div>

            </div>



            <div
                class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden"
            >

                <?php if ($totalRecipes > 0): ?>

                    <div class="overflow-x-auto">

                        <table class="w-full">

                            <thead class="bg-gray-50">

                                <tr>

                                    <th class="text-left px-6 py-4 text-sm text-gray-600">
                                        Recipe
                                    </th>

                                    <th class="text-left px-6 py-4 text-sm text-gray-600">
                                        Author
                                    </th>

                                    <th class="text-left px-6 py-4 text-sm text-gray-600">
                                        Category
                                    </th>

                                    <th class="text-left px-6 py-4 text-sm text-gray-600">
                                        Status
                                    </th>

                                    <th class="text-left px-6 py-4 text-sm text-gray-600">
                                        Date
                                    </th>

                                    <th class="text-left px-6 py-4 text-sm text-gray-600">
                                        Actions
                                    </th>

                                </tr>

                            </thead>


                            <tbody class="divide-y divide-gray-200">


                            <?php foreach ($recipes as $recipe): ?>

                                <tr class="hover:bg-gray-50">


                                    <td class="px-6 py-4">

                                        <div class="flex items-center">


                                            <?php if (!empty($recipe['image'])): ?>

                                                <img
                                                    src="../<?php echo htmlspecialchars($recipe['image']); ?>"
                                                    alt="Recipe"
                                                    class="w-14 h-14 rounded-lg object-cover"
                                                >

                                            <?php else: ?>

                                                <div
                                                    class="w-14 h-14 rounded-lg bg-gray-200 flex items-center justify-center"
                                                >
                                                    <i class="fas fa-image text-gray-400"></i>
                                                </div>

                                            <?php endif; ?>


                                            <div class="ml-4">

                                                <p class="font-semibold text-gray-800">

                                                    <?php
                                                    echo htmlspecialchars(
                                                        $recipe['title']
                                                    );
                                                    ?>

                                                </p>


                                                <p class="text-xs text-gray-500">
                                                    Recipe #<?php echo (int)$recipe['id']; ?>
                                                </p>

                                            </div>

                                        </div>

                                    </td>



                                    <td class="px-6 py-4">

                                        <p class="font-medium text-gray-800">

                                            <?php
                                            echo htmlspecialchars(
                                                $recipe['author'] ?? 'Unknown'
                                            );
                                            ?>

                                        </p>

                                        <p class="text-xs text-gray-500">

                                            <?php
                                            echo htmlspecialchars(
                                                $recipe['author_email'] ?? ''
                                            );
                                            ?>

                                        </p>

                                    </td>



                                    <td class="px-6 py-4">

                                        <?php
                                        echo htmlspecialchars(
                                            $recipe['category'] ?? 'Unknown'
                                        );
                                        ?>

                                    </td>



                                    <td class="px-6 py-4">


                                        <?php if ((int)$recipe['featured'] === 1): ?>

                                            <span
                                                class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-semibold"
                                            >
                                                <i class="fas fa-star mr-1"></i>
                                                Featured
                                            </span>

                                        <?php else: ?>

                                            <span
                                                class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-xs font-semibold"
                                            >
                                                Normal
                                            </span>

                                        <?php endif; ?>


                                    </td>



                                    <td class="px-6 py-4 text-gray-500">

                                        <?php
                                        echo date(
                                            'd M Y',
                                            strtotime($recipe['created_at'])
                                        );
                                        ?>

                                    </td>



                                    <td class="px-6 py-4">

                                        <div class="flex gap-2">


                                            <form
                                                method="POST"
                                                action="toggle_recipe_feature.php"
                                            >

                                                <input
                                                    type="hidden"
                                                    name="recipe_id"
                                                    value="<?php echo (int)$recipe['id']; ?>"
                                                >


                                                <?php if ((int)$recipe['featured'] === 1): ?>

                                                    <input
                                                        type="hidden"
                                                        name="featured"
                                                        value="0"
                                                    >

                                                    <button
                                                        type="submit"
                                                        class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-2 rounded-lg text-sm"
                                                    >
                                                        Unfeature
                                                    </button>

                                                <?php else: ?>

                                                    <input
                                                        type="hidden"
                                                        name="featured"
                                                        value="1"
                                                    >

                                                    <button
                                                        type="submit"
                                                        class="bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded-lg text-sm"
                                                    >
                                                        Feature
                                                    </button>

                                                <?php endif; ?>


                                            </form>



                                            <form
                                                method="POST"
                                                action="delete_recipe.php"
                                                onsubmit="return confirm('Are you sure you want to delete this recipe?');"
                                            >

                                                <input
                                                    type="hidden"
                                                    name="recipe_id"
                                                    value="<?php echo (int)$recipe['id']; ?>"
                                                >

                                                <button
                                                    type="submit"
                                                    class="bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded-lg text-sm"
                                                >
                                                    <i class="fas fa-trash mr-1"></i>
                                                    Delete
                                                </button>

                                            </form>


                                        </div>

                                    </td>


                                </tr>

                            <?php endforeach; ?>


                            </tbody>

                        </table>

                    </div>


                <?php else: ?>

                    <div class="p-12 text-center">

                        <i class="fas fa-utensils text-5xl text-gray-300 mb-4"></i>

                        <h3 class="text-lg font-semibold text-gray-700">
                            No recipes found
                        </h3>

                    </div>

                <?php endif; ?>

            </div>

        </div>

    </main>

</div>

</body>
</html>