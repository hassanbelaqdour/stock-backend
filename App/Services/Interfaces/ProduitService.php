<?php
namespace App\Services\Interfaces;

use App\Models\Produit;

interface ProduitService
{
    public function getProduit(int $id): Produit;

    public function getProduits(): array;

    public function createProduit(array $data): Produit;

    public function updateProduit(int $id, array $data): Produit;

    public function deleteProduit(int $id): Produit;
}
