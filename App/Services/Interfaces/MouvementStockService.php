<?php
namespace App\Services\Interfaces;

use App\Models\MouvementStock;

interface MouvementStockService
{
    public function getMouvementStock(int $id): MouvementStock;

    public function getMouvementsStocks(): array;

    public function createMouvementStock(array $data): MouvementStock;

    public function updateMouvementStock(int $id, array $data): MouvementStock;

    public function deleteMouvementStock(int $id): MouvementStock;
}
