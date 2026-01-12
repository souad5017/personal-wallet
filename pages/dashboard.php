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

$userId   = Session::get('user_id');
$userName = Session::get('user_name');

$walletModel   = new Wallet();
$expenseModel  = new Expense();
$categoryModel = new Category();

$month = date('m');
$year  = date('Y');

$wallet = $walletModel->getByUserAndMonth($userId, $month, $year);
if (!$wallet) {
    $walletModel->create($userId, $month, $year);
    $wallet = $walletModel->getByUserAndMonth($userId, $month, $year);
}

$walletId      = $wallet['id'];
$budget        = $wallet['budget'];
$balance       = $wallet['balance'];
$expensesTotal = $walletModel->getTotalExpenses($walletId);
$restant       = $balance - $expensesTotal;

$allExpenses = $expenseModel->getByWallet($walletId);
$categories  = $categoryModel->getAll();
?>

<?php include __DIR__ . '/partials/header.php'; ?>

<div class="min-h-screen bg-gradient-to-b from-white to-slate-100">
    <div class="max-w-7xl mx-auto px-6 py-10">

        <h1 class="text-4xl font-bold text-slate-800">Dashboard</h1>
        <p class="text-slate-500 mt-1">
            Bienvenue <?= htmlspecialchars($userName) ?> — <?= $month ?>/<?= $year ?>
        </p>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 my-10">

            <!-- Wallet Mensuel -->
            <div class="bg-indigo-50 rounded-2xl p-6 shadow flex flex-col gap-3">
                <p class="text-sm text-indigo-600">Wallet mensuel</p>
                <p class="text-2xl font-semibold text-indigo-800 mt-2">
                    <?= number_format($balance, 2) ?> DH
                </p>
                <p class="text-sm text-indigo-600">Budget mensuel</p>
                <p class="text-2xl font-semibold text-indigo-800 mt-2">
                    <?= number_format($budget, 2) ?> DH
                </p>

                <a href="/wallit_system/pages/manage_wallet.php?wallet_id=<?= $walletId ?>"
                   class="mt-3 bg-violet-500 text-white py-2 rounded-xl text-center hover:bg-violet-600">
                   Gérer Wallet
                </a>
            </div>

            <div class="bg-indigo-50 rounded-2xl p-6 shadow">
                <p class="text-sm text-indigo-600">Total dépenses</p>
                <p class="text-2xl font-semibold text-red-500"><?= number_format($expensesTotal,2) ?> DH</p>
            </div>

            <div class="bg-indigo-50 rounded-2xl p-6 shadow">
                <p class="text-sm text-indigo-600">Restant</p>
                <p class="text-2xl font-semibold text-indigo-800"><?= number_format($restant,2) ?> DH</p>
            </div>

            <div class="bg-indigo-50 rounded-2xl p-6 shadow flex flex-col gap-3">
                <a href="/wallit_system/pages/add_expense.php" class="bg-indigo-500 text-white py-2 rounded-xl text-center">+ Dépense</a>
                <a href="/wallit_system/pages/add_category.php" class="bg-violet-500 text-white py-2 rounded-xl text-center">+ Catégorie</a>
            </div>

        </div>

        <div class="bg-indigo-50 rounded-2xl p-8 shadow mb-10">
            <h2 class="text-xl font-semibold text-indigo-800 mb-4">Catégories</h2>
            <div class="flex gap-3 flex-wrap">
                <?php foreach ($categories as $cat): ?>
                    <span class="px-4 py-1.5 rounded-full bg-indigo-100 text-indigo-700">
                        <?= htmlspecialchars($cat['name']) ?>
                    </span>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="bg-indigo-50 rounded-2xl p-8 shadow">
            <h2 class="text-xl font-semibold text-indigo-800 mb-6">Dépenses</h2>

            <?php if (!$allExpenses): ?>
                <p class="text-indigo-400">Aucune dépense</p>
            <?php else: ?>
                <table class="w-full">
                    <thead>
                        <tr class="text-left text-sm text-indigo-400 border-b">
                            <th>Titre</th>
                            <th>Catégorie</th>
                            <th class="text-right">Montant</th>
                            <th class="text-center">Date</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($allExpenses as $exp): ?>
                            <tr class="border-b">
                                <td class="py-3"><?= htmlspecialchars($exp['title']) ?></td>
                                <td><?= htmlspecialchars($exp['category']) ?></td>
                                <td class="text-right text-red-500"><?= number_format($exp['amount'],2) ?> DH</td>
                                <td class="text-center"><?= $exp['expense_date'] ?></td>
                                <td class="text-center flex justify-center gap-2">
                                    <a href="/wallit_system/pages/edit_expense.php?id=<?= $exp['id'] ?>"
                                       class="px-3 py-1 text-xs bg-amber-100 text-amber-700 rounded">
                                        Modifier
                                    </a>

                                    <form method="POST" action="/wallit_system/actions/expense/delete.php">
                                        <input type="hidden" name="id" value="<?= $exp['id'] ?>">
                                        <button class="px-3 py-1 text-xs bg-rose-100 text-rose-700 rounded">
                                            Supprimer
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>

    </div>
</div>

<?php include __DIR__ . '/partials/footer.php'; ?>
