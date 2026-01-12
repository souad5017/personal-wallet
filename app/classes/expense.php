<?php
namespace Pc\WallitSystem\Classes;

use Pc\WallitSystem\Core\Database;
use Pc\WallitSystem\Traits\AmountFormatter;
use PDO;

class Expense {
    use AmountFormatter;

    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function create(
        int $walletId,
        int $categoryId,
        string $title,
        float $amount,
        bool $isAutomatic = false,
        ?string $date = null
    ): bool {
        $expenseDate = $date ?? date('Y-m-d');
        $stmt = $this->db->prepare(
            "INSERT INTO expenses (wallet_id, category_id, title, amount, expense_date, is_automatic)
             VALUES (:wallet_id, :category_id, :title, :amount, :expense_date, :is_automatic)"
        );
        $result = $stmt->execute([
            'wallet_id' => $walletId,
            'category_id' => $categoryId,
            'title' => $title,
            'amount' => $amount,
            'expense_date' => $expenseDate,
            'is_automatic' => $isAutomatic ? 1 : 0
        ]);

        if ($result) {
            $transaction = new Transaction();
            $transaction->create($walletId, -$amount, 'expense', $title);
        }

        return $result;
    }

    public function getByWallet(int $walletId, bool $withSign = false): array {
        $stmt = $this->db->prepare(
            "SELECT e.*, c.name AS category
             FROM expenses e
             JOIN categories c ON c.id = e.category_id
             WHERE e.wallet_id = :wallet_id
             ORDER BY e.expense_date DESC"
        );
        $stmt->execute(['wallet_id' => $walletId]);
        $expenses = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($expenses as &$exp) {
            $exp['formatted_amount'] = $this->formatAmountWithSign($exp['amount'], $withSign);
        }
        return $expenses;
    }

    public function update(int $expenseId, string $title, float $amount, int $categoryId): bool {
        $stmt = $this->db->prepare(
            "UPDATE expenses SET title = :title, amount = :amount, category_id = :category_id WHERE id = :id"
        );
        return $stmt->execute([
            'title' => $title,
            'amount' => $amount,
            'category_id' => $categoryId,
            'id' => $expenseId
        ]);
    }

public function getById(int $id): ?array {
    $stmt = $this->db->prepare("SELECT * FROM expenses WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $expense = $stmt->fetch(PDO::FETCH_ASSOC);
    return $expense ?: null;
}

public function delete(int $id): bool {
    $stmt = $this->db->prepare("DELETE FROM expenses WHERE id = :id");
    return $stmt->execute(['id' => $id]);
}

public function getTotalByWallet(int $walletId): float {
    $stmt = $this->db->prepare("SELECT SUM(amount) AS total FROM expenses WHERE wallet_id = :wallet_id");
    $stmt->execute(['wallet_id' => $walletId]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return (float) ($result['total'] ?? 0);
}

}