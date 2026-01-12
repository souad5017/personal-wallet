<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Pc\WallitSystem\Core\Session;
use Pc\WallitSystem\Classes\User;

Session::start();

if (Session::has('user_id')) {
    header('Location: /wallit_system/pages/dashboard.php');
    exit;
}

$error = Session::flash('error');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '' || $password === '') {
        Session::flash('error', 'Tous les champs sont requis');
        header('Location: /wallit_system/pages/login.php');
        exit;
    }

    $userModel = new User();
    $user = $userModel->login($email, $password);

    if (!$user) {
        Session::flash('error', 'Email ou mot de passe incorrect');
        header('Location: /wallit_system/pages/login.php');
        exit;
    }

    Session::set('user_id', $user['id']);
    Session::set('user_name', $user['name']);

    header('Location: /wallit_system/pages/dashboard.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Login - Personal Wallet</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">

<div class="bg-white p-8 rounded shadow-md w-full max-w-md">
    <h1 class="text-2xl font-bold mb-6 text-center">Connexion</h1>

    <?php if ($error): ?>
        <div class="bg-red-100 text-red-700 p-3 mb-4 rounded">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="/wallit_system/pages/login.php">
        <div class="mb-4">
            <label class="block mb-1 font-semibold" for="email">Email</label>
            <input type="email" name="email" id="email" class="w-full border px-3 py-2 rounded" required>
        </div>

        <div class="mb-6">
            <label class="block mb-1 font-semibold" for="password">Mot de passe</label>
            <input type="password" name="password" id="password" class="w-full border px-3 py-2 rounded" required>
        </div>

        <button type="submit" class="w-full bg-teal-500 text-white py-2 rounded hover:bg-teal-600">
            Se connecter
        </button>
    </form>

    <p class="mt-4 text-center text-sm">
        Pas encore inscrit? <a href="/wallit_system/pages/register.php" class="text-teal-500 hover:underline">Créer un compte</a>
    </p>
</div>

</body>
</html>
