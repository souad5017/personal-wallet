<?php
namespace Pc\WallitSystem\Interfaces;

interface Calculable {
    public function getTotal(): float;
    public function getByMonth(int $month, int $year): ?array; 
}
