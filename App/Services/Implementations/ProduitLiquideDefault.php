<?php
namespace App\Services\Implementations;

use App\Repositories\ProduitLiquideRepository;
use App\Services\Interfaces\ProduitLiquideService;
use App\Models\ProduitLiquide;
use Exception;

class ProduitLiquideDefault implements ProduitLiquideService
{
    private ProduitLiquideRepository $produitLiquideRepository;

    public function __construct()
    {
        $this->produitLiquideRepository = new ProduitLiquideRepository();
    }

    public function getProduitLiquide(int $id): ProduitLiquide
    {
        return $this->produitLiquideRepository->findById($id);
    }

    public function getProduitsLiquides(): array
    {
        return $this->produitLiquideRepository->findAllProduitsLiquides();
    }

    public function createProduitLiquide(array $data): ProduitLiquide
    {
        $produit = new ProduitLiquide(
            null,
            $data['nom'],
            $data['quantite'],
            $data['unite'] ?? null,
            $data['categorie_produit_id'] ?? null
        );

        $insertData = [
            'nom' => $produit->getNom(),
            'quantite' => $produit->getQuantite(),
            'unite' => $produit->getUnite(),
            'categorie_produit_id' => $produit->getCategorieProduitId(),
        ];

        if ($this->produitLiquideRepository->save($insertData)) {
            $lastId = $this->produitLiquideRepository->lastInsertId();
            $produit->setId($lastId);
            return $produit;
        }

        throw new Exception('impossible de creer le produit liquide');
    }

    public function updateProduitLiquide(int $id, array $data): ProduitLiquide
    {
        $produit = $this->produitLiquideRepository->findById($id);
        $updateData = [];

        if (isset($data['nom'])) {
            $produit->setNom($data['nom']);
            $updateData['nom'] = $data['nom'];
        }
        if (isset($data['quantite'])) {
            $produit->setQuantite($data['quantite']);
            $updateData['quantite'] = $data['quantite'];
        }
        if (array_key_exists('unite', $data)) {
            $produit->setUnite($data['unite']);
            $updateData['unite'] = $data['unite'];
        }
        if (array_key_exists('categorie_produit_id', $data)) {
            $produit->setCategorieProduitId($data['categorie_produit_id']);
            $updateData['categorie_produit_id'] = $data['categorie_produit_id'];
        }

        if (empty($updateData)) {
            throw new Exception('aucune donnee valide pour la mise a jour');
        }

        $this->produitLiquideRepository->update($updateData, ['id' => $id]);

        return $this->produitLiquideRepository->findById($id);
    }

    public function deleteProduitLiquide(int $id): ProduitLiquide
    {
        $produit = $this->produitLiquideRepository->findById($id);
        if ($this->produitLiquideRepository->delete(['id' => $id])) {
            return $produit;
        }
        throw new Exception('impossible de supprimer le produit liquide');
    }
}
