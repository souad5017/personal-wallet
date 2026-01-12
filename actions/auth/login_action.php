<?php
require_once __DIR__ . '/../../vendor/autoload.php';

use Pc\WallitSystem\Core\Session;
use Pc\WallitSystem\Classes\User;

Session::start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /wallit_system/pages/login.php');
    exit;
}

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
