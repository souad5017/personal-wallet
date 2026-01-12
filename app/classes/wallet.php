<?php
namespace Pc\WallitSystem\Classes;

use Pc\WallitSystem\Core\Database;
use Pc\WallitSystem\Interfaces\Calculable;
use PDO;

class Wallet implements Calculable {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function create(int $userId, int $month, int $year, float $budget = 0): bool {
        $stmt = $this->db->prepare(
            "INSERT INTO wallets (user_id, month, year, budget, balance)
             VALUES (:user_id, :month, :year, :budget, 0)"
        );
        return $stmt->execute([
            'user_id' => $userId,
            'month' => $month,
            'year' => $year,
            'budget' => $budget
        ]);
    }

    public function getByMonth(int $month, int $year): ?array {
        $stmt = $this->db->prepare(
            "SELECT * FROM wallets WHERE month = :month AND year = :year"
        );
        $stmt->execute(['month' => $month, 'year' => $year]);
        $wallet = $stmt->fetch(PDO::FETCH_ASSOC);
        return $wallet ?: null;
    }

    public function getByUserAndMonth(int $userId, int $month, int $year): ?array {
        $stmt = $this->db->prepare(
            "SELECT * FROM wallets WHERE user_id = :user AND month = :month AND year = :year"
        );
        $stmt->execute(['user' => $userId, 'month' => $month, 'year' => $year]);
        $wallet = $stmt->fetch(PDO::FETCH_ASSOC);
        return $wallet ?: null;
    }

    public function getById(int $walletId): ?array {
        $stmt = $this->db->prepare("SELECT * FROM wallets WHERE id = :id");
        $stmt->execute(['id' => $walletId]);
        $wallet = $stmt->fetch(PDO::FETCH_ASSOC);
        return $wallet ?: null;
    }

    public function updateBalance(int $walletId, float $amount): bool {
        $stmt = $this->db->prepare(
            "UPDATE wallets SET balance = balance + :amount WHERE id = :wallet_id"
        );
        return $stmt->execute(['amount' => $amount, 'wallet_id' => $walletId]);
    }

    public function setBalance(int $walletId, float $balance): bool {
        $stmt = $this->db->prepare(
            "UPDATE wallets SET balance = :balance WHERE id = :wallet_id"
        );
        return $stmt->execute(['balance' => $balance, 'wallet_id' => $walletId]);
    }

    public function updateBudget(int $walletId, float $budget): bool {
        $stmt = $this->db->prepare(
            "UPDATE wallets SET budget = :budget WHERE id = :wallet_id"
        );
        return $stmt->execute(['budget' => $budget, 'wallet_id' => $walletId]);
    }

    public function getTotal(): float {
        $stmt = $this->db->query("SELECT SUM(balance) AS total FROM wallets");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (float) ($result['total'] ?? 0);
    }

    public function getTotalExpenses(int $walletId): float {
        $stmt = $this->db->prepare("SELECT SUM(amount) AS total FROM expenses WHERE wallet_id = :wallet_id");
        $stmt->execute(['wallet_id' => $walletId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (float) ($result['total'] ?? 0);
    }

    public function getExpenses(int $walletId): array {
        $stmt = $this->db->prepare(
            "SELECT e.title, e.amount, e.expense_date, c.name AS category
             FROM expenses e
             JOIN categories c ON c.id = e.category_id
             WHERE e.wallet_id = :wallet_id
             ORDER BY e.expense_date DESC"
        );
        $stmt->execute(['wallet_id' => $walletId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
