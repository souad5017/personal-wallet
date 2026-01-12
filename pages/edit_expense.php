<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Pc\WallitSystem\Core\Session;
use Pc\WallitSystem\Classes\Wallet;
use Pc\WallitSystem\Classes\Category;
use Pc\WallitSystem\Classes\Expense;

Session::start();

if (!Session::has('user_id')) {
    header('Location: /wallit_system/pages/login.php');
    exit;
}

$userId = Session::get('user_id');

if (!isset($_GET['id'])) {
    header('Location: /wallit_system/pages/dashboard.php');
    exit;
}

$expenseId     = (int) $_GET['id'];
$expenseModel  = new Expense();
$categoryModel = new Category();
$walletModel   = new Wallet();

$expense = $expenseModel->getById($expenseId);

if (!$expense) {
    header('Location: /wallit_system/pages/dashboard.php');
    exit;
}

$categories = $categoryModel->getAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title       = trim($_POST['title']);
    $amount      = (float) $_POST['amount'];
    $category_id = (int) $_POST['category_id'];
    $date        = $_POST['expense_date'];

    $expenseModel->update($expenseId, $title, $amount, $category_id, $date);

    header('Location: /wallit_system/pages/dashboard.php');
    exit;
}
?>

<?php include __DIR__ . '/partials/header.php'; ?>

<div class="min-h-screen flex items-center justify-center bg-slate-100">
    <div class="bg-white p-8 rounded-xl shadow-lg w-full max-w-md">

        <h2 class="text-2xl font-bold text-indigo-800 mb-6">Modifier la dépense</h2>

        <form method="POST">
            <label class="block mb-2 text-indigo-700">Titre</label>
            <input type="text" name="title" value="<?= htmlspecialchars($expense['title']) ?>"
                   class="w-full mb-4 p-2 border rounded" required>

            <label class="block mb-2 text-indigo-700">Montant (DH)</label>
            <input type="number" step="0.01" name="amount" value="<?= $expense['amount'] ?>"
                   class="w-full mb-4 p-2 border rounded" required>

            <label class="block mb-2 text-indigo-700">Catégorie</label>
            <select name="category_id" class="w-full mb-4 p-2 border rounded" required>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['id'] ?>" <?= $expense['category_id'] == $cat['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label class="block mb-2 text-indigo-700">Date</label>
            <input type="date" name="expense_date" value="<?= $expense['expense_date'] ?>"
                   class="w-full mb-6 p-2 border rounded" required>

            <button type="submit"
                    class="w-full bg-amber-500 text-white py-2 rounded hover:bg-amber-600">
                Enregistrer
            </button>
        </form>

        <a href="/wallit_system/pages/dashboard.php"
           class="block mt-4 text-center text-indigo-500 hover:underline">
           Annuler
        </a>
    </div>
</div>

<?php include __DIR__ . '/partials/footer.php'; ?>
