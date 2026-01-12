<?php
namespace Pc\WallitSystem\Classes;

use Pc\WallitSystem\Core\Database;
use PDO;

class Transaction {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function create(int $walletId, float $amount, string $type, ?string $title = null): bool {
        $stmt = $this->db->prepare(
            "INSERT INTO transactions (wallet_id, amount, type, title)
             VALUES (:wallet_id, :amount, :type, :title)"
        );
        return $stmt->execute([
            'wallet_id' => $walletId,
            'amount' => $amount,
            'type' => $type,
            'title' => $title
        ]);
    }

    public function getByWallet(int $walletId): array {
        $stmt = $this->db->prepare(
            "SELECT * FROM transactions 
             WHERE wallet_id = :wallet_id
             ORDER BY created_at DESC"
        );
        $stmt->execute(['wallet_id' => $walletId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
