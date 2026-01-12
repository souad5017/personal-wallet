<?php
namespace Pc\WallitSystem\Traits;

trait AmountFormatter {
    public function formatAmountWithSign(float $amount, bool $withSign = false): string {
        $formatted = number_format($amount, 2) . ' DH';
        if ($withSign) {
            $formatted = ($amount < 0 ? '-' : '+') . ' ' . $formatted;
        }
        return $formatted;
    }

    public function displayAmount(float $amount): string {
        return $this->formatAmountWithSign($amount);
    }
}