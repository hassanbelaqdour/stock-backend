<?php
namespace App\Services\Interfaces;

use App\Models\ProduitLiquide;

interface ProduitLiquideService
{
    public function getProduitLiquide(int $id): ProduitLiquide;

    public function getProduitsLiquides(): array;

    public function createProduitLiquide(array $data): ProduitLiquide;

    public function updateProduitLiquide(int $id, array $data): ProduitLiquide;

    public function deleteProduitLiquide(int $id): ProduitLiquide;
}
