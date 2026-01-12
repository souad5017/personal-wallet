<?php
use Pc\WallitSystem\Core\Session;

require_once __DIR__ . '/../../vendor/autoload.php';
Session::start();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Personal Wallet</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

<nav class="w-full bg-white shadow-sm">
    <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">

        <div class="text-2xl font-bold text-indigo-600">
            Personal Wallet
        </div>

        <div class="flex items-center gap-4">
            <?php if (Session::has('user_id')): ?>
                <a href="/wallit_system/pages/dashboard.php"
                   class="px-4 py-2 rounded-xl text-indigo-600 font-medium hover:text-indigo-800 transition">
                    Dashboard
                </a>

                <a href="/wallit_system/pages/logout.php"
                   class="px-4 py-2 rounded-xl bg-violet-500 text-white font-medium hover:bg-violet-600 transition">
                    Logout
                </a>
            <?php else: ?>
                <a href="/wallit_system/pages/login.php"
                   class="px-4 py-2 rounded-xl border border-indigo-500 text-indigo-600 font-medium
                          hover:bg-indigo-500 hover:text-white transition">
                    Login
                </a>

                <a href="/wallit_system/pages/register.php"
                   class="px-4 py-2 rounded-xl bg-indigo-500 text-white font-medium
                          hover:bg-indigo-600 transition">
                    Register
                </a>
            <?php endif; ?>
        </div>

    </div>
</nav>



<main class="max-w-7xl mx-auto px-4 py-6">
