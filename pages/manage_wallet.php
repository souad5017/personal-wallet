<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Pc\WallitSystem\Core\Session;
use Pc\WallitSystem\Classes\Wallet;

Session::start();
if (!Session::has('user_id')) {
    header('Location: login.php');
    exit;
}

$walletModel = new Wallet();
$userId = Session::get('user_id');
$month = date('m'); 
$year  = date('Y');

$wallet = $walletModel->getByUserAndMonth($userId, $month, $year);

if (!$wallet) {
    $walletModel->create($userId, $month, $year);
    $wallet = $walletModel->getByUserAndMonth($userId, $month, $year);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $budget = (float) $_POST['budget'];
    $balance = (float) $_POST['balance'];

    $walletModel->setBalance($wallet['id'], $balance);
    $walletModel->updateBudget($wallet['id'], $budget);

    header('Location: dashboard.php');
    exit;
}
?>

<?php include __DIR__ . '/partials/header.php'; ?>

<div class="min-h-screen bg-gradient-to-b from-white to-slate-100 flex items-center justify-center p-6">
    <div class="bg-white rounded-2xl shadow-lg p-10 w-full max-w-md">
        <h1 class="text-3xl font-bold text-slate-800 mb-6 text-center">Gérer Wallet</h1>

        <form method="POST" class="space-y-6">
            <div>
                <label class="block text-slate-600 font-medium mb-1">Solde</label>
                <input 
                    type="number" 
                    step="0.01" 
                    name="balance" 
                    value="<?= $wallet['balance'] ?>" 
                    required
                    class="w-full px-4 py-3 border rounded-xl border-slate-300 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                >
            </div>

            <div>
                <label class="block text-slate-600 font-medium mb-1">Budget Mensuel</label>
                <input 
                    type="number" 
                    step="0.01" 
                    name="budget" 
                    value="<?= $wallet['budget'] ?>" 
                    required
                    class="w-full px-4 py-3 border rounded-xl border-slate-300 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                >
            </div>
            <button 
                type="submit"
                class="w-full bg-indigo-600 text-white font-semibold py-3 rounded-xl hover:bg-indigo-700 transition-colors"
            >
                Mettre à jour
            </button>

            <a href="dashboard.php" class="block text-center text-indigo-600 hover:underline mt-2">
                Retour au Dashboard
            </a>
        </form>
    </div>
</div>

<?php include __DIR__ . '/partials/footer.php'; ?>
