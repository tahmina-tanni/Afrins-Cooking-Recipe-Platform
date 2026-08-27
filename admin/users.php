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

$currentAdminId = (int)$_SESSION['user_id'];


// ==========================================
// DATABASE
// ==========================================

$conn = connectDB();

$result = $conn->query("
    SELECT
        id,
        name,
        email,
        role,
        created_at
    FROM users
    ORDER BY created_at DESC
");

$users = [];

if ($result) {

    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }

}

$totalUsers = count($users);

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

    <title>User Management - Afrin's Cooking</title>


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


    <!-- ======================================
         SIDEBAR
    ======================================= -->

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
                href="#"
                class="block px-4 py-3 hover:bg-green-700 rounded-lg"
            >
                <i class="fas fa-utensils mr-2"></i>
                Recipes
            </a>


            <a
                href="users.php"
                class="block px-4 py-3 bg-green-700 rounded-lg"
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



    <!-- ======================================
         MAIN CONTENT
    ======================================= -->

    <main class="flex-1">


        <!-- HEADER -->

        <header
            class="bg-white shadow-sm px-8 py-5 flex justify-between items-center"
        >

            <div>

                <h2 class="text-2xl font-bold text-gray-800">
                    User Management
                </h2>

                <p class="text-gray-500 mt-1">
                    Manage registered users and roles
                </p>

            </div>


            <div class="flex items-center space-x-4">

                <div class="text-right">

                    <p class="font-semibold text-gray-800">
                        <?php echo $adminName; ?>
                    </p>

                    <p class="text-xs text-gray-500">
                        Administrator
                    </p>

                </div>


                <a
                    href="../index.html"
                    class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded-lg"
                >
                    <i class="fas fa-external-link-alt mr-1"></i>
                    View Website
                </a>

            </div>

        </header>



        <!-- ======================================
             CONTENT
        ======================================= -->

        <div class="p-8">


            <!-- SUCCESS MESSAGE -->

            <?php if (isset($_GET['success'])): ?>

                <div
                    class="mb-6 bg-green-100 border border-green-300 text-green-800 px-5 py-4 rounded-lg"
                >

                    <i class="fas fa-check-circle mr-2"></i>

                    <?php
                    echo htmlspecialchars(
                        $_GET['success']
                    );
                    ?>

                </div>

            <?php endif; ?>



            <!-- ERROR MESSAGE -->

            <?php if (isset($_GET['error'])): ?>

                <div
                    class="mb-6 bg-red-100 border border-red-300 text-red-800 px-5 py-4 rounded-lg"
                >

                    <i class="fas fa-exclamation-circle mr-2"></i>

                    <?php
                    echo htmlspecialchars(
                        $_GET['error']
                    );
                    ?>

                </div>

            <?php endif; ?>



            <!-- PAGE TITLE -->

            <div
                class="flex justify-between items-center mb-6"
            >

                <div>

                    <h3
                        class="text-xl font-semibold text-gray-800"
                    >
                        Registered Users
                    </h3>

                    <p class="text-gray-500 text-sm mt-1">
                        View users and manage account roles
                    </p>

                </div>


                <div
                    class="bg-green-100 text-green-800 px-4 py-2 rounded-lg font-semibold"
                >

                    <i class="fas fa-users mr-2"></i>

                    Total Users:
                    <?php echo $totalUsers; ?>

                </div>

            </div>



            <!-- ======================================
                 USERS TABLE
            ======================================= -->

            <div
                class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden"
            >


                <?php if (count($users) > 0): ?>


                    <div class="overflow-x-auto">

                        <table class="w-full">


                            <thead class="bg-gray-50">

                                <tr>


                                    <th
                                        class="text-left px-6 py-4 text-sm font-semibold text-gray-600"
                                    >
                                        ID
                                    </th>


                                    <th
                                        class="text-left px-6 py-4 text-sm font-semibold text-gray-600"
                                    >
                                        User
                                    </th>


                                    <th
                                        class="text-left px-6 py-4 text-sm font-semibold text-gray-600"
                                    >
                                        Email
                                    </th>


                                    <th
                                        class="text-left px-6 py-4 text-sm font-semibold text-gray-600"
                                    >
                                        Role
                                    </th>


                                    <th
                                        class="text-left px-6 py-4 text-sm font-semibold text-gray-600"
                                    >
                                        Joined
                                    </th>


                                    <th
                                        class="text-left px-6 py-4 text-sm font-semibold text-gray-600"
                                    >
                                        Action
                                    </th>


                                </tr>

                            </thead>



                            <tbody class="divide-y divide-gray-200">


                            <?php foreach ($users as $user): ?>


                                <tr
                                    class="hover:bg-gray-50 transition"
                                >


                                    <!-- ID -->

                                    <td class="px-6 py-4">

                                        <span class="text-gray-500">

                                            #<?php echo (int)$user['id']; ?>

                                        </span>

                                    </td>



                                    <!-- USER -->

                                    <td class="px-6 py-4">

                                        <div class="flex items-center">


                                            <div
                                                class="w-10 h-10 bg-green-100 text-green-700 rounded-full flex items-center justify-center font-bold"
                                            >

                                                <?php

                                                echo strtoupper(
                                                    substr(
                                                        $user['name'],
                                                        0,
                                                        1
                                                    )
                                                );

                                                ?>

                                            </div>


                                            <div class="ml-3">

                                                <p
                                                    class="font-semibold text-gray-800"
                                                >

                                                    <?php

                                                    echo htmlspecialchars(
                                                        $user['name']
                                                    );

                                                    ?>

                                                </p>


                                                <?php
                                                if (
                                                    (int)$user['id']
                                                    === $currentAdminId
                                                ):
                                                ?>

                                                    <span
                                                        class="text-xs text-green-600 font-semibold"
                                                    >
                                                        You
                                                    </span>

                                                <?php endif; ?>


                                            </div>


                                        </div>

                                    </td>



                                    <!-- EMAIL -->

                                    <td
                                        class="px-6 py-4 text-gray-600"
                                    >

                                        <?php

                                        echo htmlspecialchars(
                                            $user['email']
                                        );

                                        ?>

                                    </td>



                                    <!-- ROLE -->

                                    <td class="px-6 py-4">


                                        <?php
                                        if ($user['role'] === 'admin'):
                                        ?>


                                            <span
                                                class="inline-flex items-center bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-semibold"
                                            >

                                                <i
                                                    class="fas fa-shield-alt mr-1"
                                                ></i>

                                                Admin

                                            </span>


                                        <?php else: ?>


                                            <span
                                                class="inline-flex items-center bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-xs font-semibold"
                                            >

                                                <i
                                                    class="fas fa-user mr-1"
                                                ></i>

                                                User

                                            </span>


                                        <?php endif; ?>


                                    </td>



                                    <!-- JOIN DATE -->

                                    <td
                                        class="px-6 py-4 text-gray-500"
                                    >

                                        <?php

                                        echo date(
                                            'd M Y',
                                            strtotime(
                                                $user['created_at']
                                            )
                                        );

                                        ?>

                                    </td>



                                    <!-- ACTION -->

                                    <td class="px-6 py-4">


                                        <?php
                                        if (
                                            (int)$user['id']
                                            === $currentAdminId
                                        ):
                                        ?>


                                            <span
                                                class="text-gray-400 text-sm"
                                            >

                                                <i
                                                    class="fas fa-lock mr-1"
                                                ></i>

                                                Protected

                                            </span>


                                        <?php else: ?>


                                            <form
                                                method="POST"
                                                action="update_user_role.php"
                                                onsubmit="return confirmRoleChange(this);"
                                            >


                                                <input
                                                    type="hidden"
                                                    name="user_id"
                                                    value="<?php echo (int)$user['id']; ?>"
                                                >


                                                <?php
                                                if ($user['role'] === 'user'):
                                                ?>


                                                    <input
                                                        type="hidden"
                                                        name="role"
                                                        value="admin"
                                                    >


                                                    <button
                                                        type="submit"
                                                        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-semibold"
                                                    >

                                                        <i
                                                            class="fas fa-user-shield mr-1"
                                                        ></i>

                                                        Make Admin

                                                    </button>


                                                <?php else: ?>


                                                    <input
                                                        type="hidden"
                                                        name="role"
                                                        value="user"
                                                    >


                                                    <button
                                                        type="submit"
                                                        class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg text-sm font-semibold"
                                                    >

                                                        <i
                                                            class="fas fa-user mr-1"
                                                        ></i>

                                                        Make User

                                                    </button>


                                                <?php endif; ?>


                                            </form>


                                        <?php endif; ?>


                                    </td>


                                </tr>


                            <?php endforeach; ?>


                            </tbody>


                        </table>

                    </div>


                <?php else: ?>


                    <div class="p-12 text-center">

                        <i
                            class="fas fa-users text-5xl text-gray-300 mb-4"
                        ></i>

                        <h3
                            class="text-lg font-semibold text-gray-700"
                        >
                            No users found
                        </h3>

                    </div>


                <?php endif; ?>


            </div>


        </div>


    </main>


</div>



<script>

function confirmRoleChange(form) {

    const newRole =
        form.querySelector(
            'input[name="role"]'
        ).value;


    if (newRole === 'admin') {

        return confirm(
            'Are you sure you want to make this user an Admin?'
        );

    }


    return confirm(
        'Are you sure you want to change this Admin to a normal User?'
    );

}

</script>


</body>

</html>