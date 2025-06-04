<?php
namespace App\Services\Interfaces;

use App\Models\CategorieProduit;

interface CategorieProduitService
{
    public function getCategorieProduit(int $id): CategorieProduit;

    public function getCategoriesProduit(): array;

    public function createCategorieProduit(array $data): CategorieProduit;

    public function updateCategorieProduit(int $id, array $data): CategorieProduit;

    public function deleteCategorieProduit(int $id): CategorieProduit;
}
