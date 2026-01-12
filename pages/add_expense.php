<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Pc\WallitSystem\Core\Session;
use Pc\WallitSystem\Classes\Wallet;
use Pc\WallitSystem\Classes\Category;
use Pc\WallitSystem\Classes\Expense;

Session::start();

if (!Session::has('user_id')) {
    header('Location: login.php');
    exit;
}

$userId = Session::get('user_id');

$walletModel = new Wallet();
$wallet = $walletModel->getByUserAndMonth($userId, date('m'), date('Y'));

if (!$wallet) {
    $walletModel->create($userId, date('m'), date('Y'));
    $wallet = $walletModel->getByUserAndMonth($userId, date('m'), date('Y'));
}

$walletId = $wallet['id'];
$categoryModel = new Category();
$categories = $categoryModel->getAll();
$expenseModel = new Expense();

$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title      = trim($_POST['title']);
    $amount     = (float) $_POST['amount'];
    $categoryId = (int) $_POST['category'];

    $expenses = $expenseModel->getByWallet($walletId);
    $totalExpenses = 0;
    foreach ($expenses as $expItem) {
        $totalExpenses += $expItem['amount'];
    }

    $restant = $wallet['balance'] - $totalExpenses;

    if ($amount > $restant) {
        $errorMessage = "Le montant dépasse le restant disponible !";
    } else {
        $expenseModel->create($walletId, $categoryId, $title, $amount);
        header('Location: dashboard.php');
        exit;
    }
}
?>

<?php include __DIR__ . '/partials/header.php'; ?>

<?php if ($errorMessage): ?>
    <div class="max-w-md mx-auto mb-4 p-3 bg-red-100 text-red-700 rounded-xl">
        <?= htmlspecialchars($errorMessage) ?>
    </div>
<?php endif; ?>

<form method="POST" class="bg-white p-6 rounded-2xl shadow-md max-w-md mx-auto space-y-4">
    <h2 class="text-xl font-semibold text-indigo-800 mb-4">Ajouter une dépense</h2>

    <input type="text" name="title" placeholder="Titre dépense" required
           class="w-full px-4 py-2 border rounded-xl">

    <input type="number" step="0.01" name="amount" placeholder="Montant" required
           class="w-full px-4 py-2 border rounded-xl">

    <select name="category" required class="w-full px-4 py-2 border rounded-xl">
        <?php foreach ($categories as $cat): ?>
            <option value="<?= $cat['id'] ?>">
                <?= htmlspecialchars($cat['name']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <button type="submit" class="w-full bg-indigo-500 text-white py-2 rounded-xl">
        Ajouter Dépense
    </button>
</form>

<?php include __DIR__ . '/partials/footer.php'; ?>
