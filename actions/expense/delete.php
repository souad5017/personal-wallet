<?php
require_once __DIR__ . '/../../vendor/autoload.php';
use Pc\WallitSystem\Classes\Expense;
use Pc\WallitSystem\Core\Session;

Session::start();
if (!Session::has('user_id')) {
    header('Location: /wallit_system/pages/login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $expenseId = (int) $_POST['id'];
    $expenseModel = new Expense();

    $expenseModel->delete($expenseId);

    header('Location: /wallit_system/pages/dashboard.php');
    exit;
}
