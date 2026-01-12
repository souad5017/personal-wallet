<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Pc\WallitSystem\Core\Session;

Session::start();

if (!Session::has('user_id')) {
    header('Location: /wallit_system/pages/login.php');
    exit;
}

$error   = Session::flash('error');
$success = Session::flash('success');
?>

<?php include __DIR__ . '/partials/header.php'; ?>

<div class="min-h-screen bg-slate-100 flex items-center justify-center">
    <div class="bg-white w-full max-w-md p-8 rounded-2xl shadow border">

        <h1 class="text-2xl font-bold text-slate-800 mb-6 text-center">
            Ajouter une catégorie
        </h1>

        <?php if ($error): ?>
            <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-4">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="bg-green-100 text-green-700 px-4 py-2 rounded mb-4">
                <?= htmlspecialchars($success) ?>
            </div>
        <?php endif; ?>

        <form action="/wallit_system/actions/auth/category.php" method="POST" class="space-y-4">

            <div>
                <label class="block text-sm text-slate-600 mb-1">
                    Nom de la catégorie
                </label>
                <input type="text" name="name"
                       class="w-full border rounded-xl px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                       required>
            </div>

            <button type="submit"
                    class="w-full bg-indigo-600 text-white py-2 rounded-xl hover:bg-indigo-700 transition">
                Ajouter
            </button>

        </form>

        <a href="/wallit_system/pages/dashboard.php"
           class="block text-center text-slate-500 mt-4 hover:underline">
            ← Retour au dashboard
        </a>

    </div>
</div>

<?php include __DIR__ . '/partials/footer.php'; ?>
