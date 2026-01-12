<?php
require_once __DIR__ . '/../../vendor/autoload.php';

use Pc\WallitSystem\Core\Session;
use Pc\WallitSystem\Classes\Category;

Session::start();

if (!Session::has('user_id')) {
    header('Location: /wallit_system/pages/login.php');
    exit;
}

$name = trim($_POST['name'] ?? '');

if ($name === '') {
    Session::flash('error', 'Le nom de la catégorie est obligatoire');
    header('Location: /wallit_system/pages/add_category.php');
    exit;
}

$category = new Category();

if ($category->exists($name)) {
    Session::flash('error', 'Cette catégorie existe déjà');
    header('Location: /wallit_system/pages/add_category.php');
    exit;
}

$category->create($name);

Session::flash('success', 'Catégorie ajoutée avec succès');
header('Location: /wallit_system/pages/add_category.php');
exit;
