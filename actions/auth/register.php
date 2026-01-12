<?php
require_once __DIR__ . '/../../vendor/autoload.php';

use Pc\WallitSystem\Core\Session;
use Pc\WallitSystem\Classes\User;
use Pc\WallitSystem\Classes\Wallet;

Session::start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /wallit_system/pages/register.php');
    exit;
}

$name     = trim($_POST['name'] ?? '');
$email    = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if ($name === '' || $email === '' || $password === '') {
    Session::flash('error', 'Tous les champs sont obligatoires');
    header('Location: /wallit_system/pages/register.php');
    exit;
}

$userModel = new User();

if ($userModel->emailExists($email)) {
    Session::flash('error', 'Cet email existe déjà');
    header('Location: /wallit_system/pages/register.php');
    exit;
}

$userModel->register($name, $email, $password);

$dbUser = $userModel->login($email, $password);
$userId = $dbUser['id'];

$wallet = new Wallet();
$currentMonth = date('n');
$currentYear  = date('Y'); 
$wallet->create($userId, $currentMonth, $currentYear); // دابا مع 3 arguments


Session::flash('success', 'Compte créé avec succès');
header('Location: /wallit_system/pages/login.php');
exit;
