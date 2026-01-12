<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Pc\WallitSystem\Core\Session;

Session::start();

$error   = Session::flash('error');
$success = Session::flash('success');
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Personal Wallet - S'inscrire</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

<div class="w-full max-w-md bg-white p-8 rounded-lg shadow-lg mx-auto mt-10">

    <h2 class="text-2xl font-bold mb-6 text-center">Créer un compte</h2>

    <?php if ($error): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            <?= htmlspecialchars($success) ?>
        </div>
    <?php endif; ?>

    <form action="/wallit_system/actions/auth/register.php" method="POST" class="space-y-4">

        <div>
            <label class="block text-gray-700 mb-2">Nom</label>
            <input type="text" name="name" placeholder="Votre nom"
                   class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500" required>
        </div>

        <div>
            <label class="block text-gray-700 mb-2">Email</label>
            <input type="email" name="email" placeholder="Votre email"
                   class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500" required>
        </div>

        <div>
            <label class="block text-gray-700 mb-2">Mot de passe</label>
            <input type="password" name="password" placeholder="Mot de passe"
                   class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500" required>
        </div>

        <button type="submit"
                class="w-full bg-teal-500 text-white font-bold py-2 px-4 rounded-lg hover:bg-teal-600 transition-colors">
            S'inscrire
        </button>

    </form>

    <p class="mt-4 text-center text-gray-600">
        Vous avez déjà un compte ?
        <a href="/wallit_system/pages/login.php" class="text-teal-500 hover:underline">Se connecter</a>
    </p>

</div>

</body>
</html>
