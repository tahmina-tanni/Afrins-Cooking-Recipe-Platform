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
// ADMIN INFO
// ==========================================

$adminName = htmlspecialchars(
    $_SESSION['user_name'] ?? 'Admin'
);


// ==========================================
// DATABASE
// ==========================================

$conn = connectDB();


// Total Users
$userResult = $conn->query(
    "SELECT COUNT(*) AS total FROM users"
);

$totalUsers = $userResult
    ? (int)$userResult->fetch_assoc()['total']
    : 0;


// Total Recipes
$recipeResult = $conn->query(
    "SELECT COUNT(*) AS total FROM recipes"
);

$totalRecipes = $recipeResult
    ? (int)$recipeResult->fetch_assoc()['total']
    : 0;


// Total Categories
$categoryResult = $conn->query(
    "SELECT COUNT(*) AS total FROM categories"
);

$totalCategories = $categoryResult
    ? (int)$categoryResult->fetch_assoc()['total']
    : 0;


// Total Reviews
$reviewResult = $conn->query(
    "SELECT COUNT(*) AS total FROM reviews"
);

$totalReviews = $reviewResult
    ? (int)$reviewResult->fetch_assoc()['total']
    : 0;


// Newsletter Subscribers
$subscriberResult = $conn->query(
    "SELECT COUNT(*) AS total FROM newsletter"
);

$totalSubscribers = $subscriberResult
    ? (int)$subscriberResult->fetch_assoc()['total']
    : 0;


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

    <title>Afrin's Cooking - Admin Panel</title>

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


    <!-- =====================================
         SIDEBAR
    ====================================== -->

    <aside class="w-64 bg-green-800 text-white p-6">

        <h1 class="text-2xl font-bold mb-2">
            Afrin's Cooking
        </h1>

        <p class="text-green-200 mb-10">
            Admin Panel
        </p>


        <nav class="space-y-3">


            <!-- Dashboard -->

            <a
                href="index.php"
                class="block px-4 py-3 bg-green-700 rounded-lg"
            >

                <i class="fas fa-chart-line mr-2"></i>

                Dashboard

            </a>


            <!-- Recipes -->

            <a
                href="recipes.php"
                class="block px-4 py-3 hover:bg-green-700 rounded-lg"
            >

                <i class="fas fa-utensils mr-2"></i>

                Recipes

            </a>


            <!-- Users -->

            <a
                href="users.php"
                class="block px-4 py-3 hover:bg-green-700 rounded-lg"
            >

                <i class="fas fa-users mr-2"></i>

                Users

            </a>


            <!-- Categories -->

            <a
                href="#"
                class="block px-4 py-3 hover:bg-green-700 rounded-lg"
            >

                <i class="fas fa-list mr-2"></i>

                Categories

            </a>


            <!-- Back to Website -->

            <a
                href="../index.html"
                class="block px-4 py-3 hover:bg-green-700 rounded-lg mt-6"
            >

                <i class="fas fa-home mr-2"></i>

                Back to Website

            </a>


        </nav>

    </aside>



    <!-- =====================================
         MAIN CONTENT
    ====================================== -->

    <main class="flex-1">


        <!-- HEADER -->

        <header
            class="bg-white shadow-sm px-8 py-5 flex justify-between items-center"
        >


            <div>

                <h2 class="text-2xl font-bold text-gray-800">
                    Admin Dashboard
                </h2>


                <p class="text-gray-500 mt-1">

                    Welcome,

                    <span class="font-semibold">

                        <?php echo $adminName; ?>

                    </span>

                </p>

            </div>


            <a
                href="../index.html"
                class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded-lg"
            >

                <i class="fas fa-external-link-alt mr-1"></i>

                View Website

            </a>


        </header>



        <!-- =====================================
             DASHBOARD CONTENT
        ====================================== -->

        <div class="p-8">


            <div class="mb-8">

                <h3 class="text-xl font-semibold text-gray-800">
                    Dashboard Overview
                </h3>

                <p class="text-gray-500 mt-1">
                    Overview of your cooking platform
                </p>

            </div>



            <!-- =====================================
                 STAT CARDS
            ====================================== -->

            <div
                class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6"
            >


                <!-- USERS -->

                <div
                    class="bg-white rounded-xl shadow-sm border border-gray-200 p-6"
                >

                    <div class="flex justify-between items-center">

                        <div>

                            <p class="text-gray-500 text-sm">
                                Total Users
                            </p>


                            <h3
                                class="text-3xl font-bold text-gray-800 mt-2"
                            >

                                <?php echo $totalUsers; ?>

                            </h3>

                        </div>


                        <div
                            class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center"
                        >

                            <i
                                class="fas fa-users text-xl text-green-600"
                            ></i>

                        </div>

                    </div>

                </div>



                <!-- RECIPES -->

                <div
                    class="bg-white rounded-xl shadow-sm border border-gray-200 p-6"
                >

                    <div class="flex justify-between items-center">

                        <div>

                            <p class="text-gray-500 text-sm">
                                Total Recipes
                            </p>


                            <h3
                                class="text-3xl font-bold text-gray-800 mt-2"
                            >

                                <?php echo $totalRecipes; ?>

                            </h3>

                        </div>


                        <div
                            class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center"
                        >

                            <i
                                class="fas fa-utensils text-xl text-green-600"
                            ></i>

                        </div>

                    </div>

                </div>



                <!-- CATEGORIES -->

                <div
                    class="bg-white rounded-xl shadow-sm border border-gray-200 p-6"
                >

                    <div class="flex justify-between items-center">

                        <div>

                            <p class="text-gray-500 text-sm">
                                Categories
                            </p>


                            <h3
                                class="text-3xl font-bold text-gray-800 mt-2"
                            >

                                <?php echo $totalCategories; ?>

                            </h3>

                        </div>


                        <div
                            class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center"
                        >

                            <i
                                class="fas fa-list text-xl text-green-600"
                            ></i>

                        </div>

                    </div>

                </div>



                <!-- REVIEWS -->

                <div
                    class="bg-white rounded-xl shadow-sm border border-gray-200 p-6"
                >

                    <div class="flex justify-between items-center">

                        <div>

                            <p class="text-gray-500 text-sm">
                                Reviews
                            </p>


                            <h3
                                class="text-3xl font-bold text-gray-800 mt-2"
                            >

                                <?php echo $totalReviews; ?>

                            </h3>

                        </div>


                        <div
                            class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center"
                        >

                            <i
                                class="fas fa-star text-xl text-yellow-500"
                            ></i>

                        </div>

                    </div>

                </div>


            </div>



            <!-- =====================================
                 SECOND ROW
            ====================================== -->

            <div class="mt-6">


                <!-- NEWSLETTER -->

                <div
                    class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 max-w-sm"
                >

                    <div class="flex justify-between items-center">

                        <div>

                            <p class="text-gray-500 text-sm">
                                Newsletter Subscribers
                            </p>


                            <h3
                                class="text-3xl font-bold text-gray-800 mt-2"
                            >

                                <?php echo $totalSubscribers; ?>

                            </h3>

                        </div>


                        <div
                            class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center"
                        >

                            <i
                                class="fas fa-envelope text-xl text-blue-600"
                            ></i>

                        </div>

                    </div>

                </div>


            </div>



            <!-- =====================================
                 QUICK ACTIONS
            ====================================== -->

            <div class="mt-10">

                <h3 class="text-xl font-semibold text-gray-800 mb-5">
                    Quick Actions
                </h3>


                <div
                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5"
                >


                    <!-- Manage Recipes -->

                    <a
                        href="recipes.php"
                        class="bg-white border border-gray-200 rounded-xl p-6 hover:shadow-md transition"
                    >

                        <div
                            class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mb-4"
                        >

                            <i
                                class="fas fa-utensils text-green-600 text-xl"
                            ></i>

                        </div>


                        <h4
                            class="font-semibold text-gray-800 text-lg"
                        >
                            Manage Recipes
                        </h4>


                        <p
                            class="text-gray-500 text-sm mt-2"
                        >
                            View, feature and delete recipes
                        </p>

                    </a>



                    <!-- Manage Users -->

                    <a
                        href="users.php"
                        class="bg-white border border-gray-200 rounded-xl p-6 hover:shadow-md transition"
                    >

                        <div
                            class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mb-4"
                        >

                            <i
                                class="fas fa-users text-blue-600 text-xl"
                            ></i>

                        </div>


                        <h4
                            class="font-semibold text-gray-800 text-lg"
                        >
                            Manage Users
                        </h4>


                        <p
                            class="text-gray-500 text-sm mt-2"
                        >
                            View users and manage roles
                        </p>

                    </a>



                    <!-- View Website -->

                    <a
                        href="../index.html"
                        class="bg-white border border-gray-200 rounded-xl p-6 hover:shadow-md transition"
                    >

                        <div
                            class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mb-4"
                        >

                            <i
                                class="fas fa-external-link-alt text-purple-600 text-xl"
                            ></i>

                        </div>


                        <h4
                            class="font-semibold text-gray-800 text-lg"
                        >
                            View Website
                        </h4>


                        <p
                            class="text-gray-500 text-sm mt-2"
                        >
                            Open the public recipe platform
                        </p>

                    </a>


                </div>

            </div>


        </div>


    </main>


</div>


</body>

</html>