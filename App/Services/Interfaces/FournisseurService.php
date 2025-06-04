<?php
namespace App\Services\Interfaces;

use App\Models\Fournisseur;

interface FournisseurService
{
    public function getFournisseur(int $id): Fournisseur;

    public function getFournisseurs(): array;

    public function createFournisseur(array $data): Fournisseur;

    public function updateFournisseur(int $id, array $data): Fournisseur;

    public function deleteFournisseur(int $id): Fournisseur;
}
