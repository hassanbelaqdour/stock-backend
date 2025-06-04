<?php
namespace App\Services\Implementations;

use App\Repositories\ProduitRepository;
use App\Services\Interfaces\ProduitService;
use App\Models\Produit;
use Exception;

class ProduitDefault implements ProduitService
{
    private ProduitRepository $produitRepository;

    public function __construct()
    {
        $this->produitRepository = new ProduitRepository();
    }

    public function getProduit(int $id): Produit
    {
        return $this->produitRepository->findById($id);
    }

    public function getProduits(): array
    {
        return $this->produitRepository->findAllProduits();
    }

    public function createProduit(array $data): Produit
    {
        $produit = new Produit(
            null,
            $data['nom'],
            $data['description'] ?? null,
            (int)$data['categorie_id'],
            (int)($data['stock'] ?? 0),
            (float)$data['prix']
        );

        $insertData = [
            'nom' => $produit->getNom(),
            'description' => $produit->getDescription(),
            'categorie_id' => $produit->getCategorieId(),
            'stock' => $produit->getStock(),
            'prix' => $produit->getPrix(),
        ];

        if ($this->produitRepository->save($insertData)) {
            $lastId = $this->produitRepository->lastInsertId();
            $produit->setId($lastId);
            return $produit;
        }

        throw new Exception('impossible de creer le produit');
    }

    public function updateProduit(int $id, array $data): Produit
    {
        $produit = $this->produitRepository->findById($id);
        $updateData = [];

        if (isset($data['nom'])) {
            $produit->setNom($data['nom']);
            $updateData['nom'] = $data['nom'];
        }
        if (array_key_exists('description', $data)) {
            $produit->setDescription($data['description']);
            $updateData['description'] = $data['description'];
        }
        if (isset($data['categorie_id'])) {
            $produit->setCategorieId((int)$data['categorie_id']);
            $updateData['categorie_id'] = (int)$data['categorie_id'];
        }
        if (isset($data['stock'])) {
            $produit->setStock((int)$data['stock']);
            $updateData['stock'] = (int)$data['stock'];
        }
        if (isset($data['prix'])) {
            $produit->setPrix((float)$data['prix']);
            $updateData['prix'] = (float)$data['prix'];
        }

        if (empty($updateData)) {
            throw new Exception('aucune donnee valide pour la mise a jour');
        }

        $this->produitRepository->update($updateData, ['id' => $id]);

        return $this->produitRepository->findById($id);
    }

    public function deleteProduit(int $id): Produit
    {
        $produit = $this->produitRepository->findById($id);
        if ($this->produitRepository->delete(['id' => $id])) {
            return $produit;
        }
        throw new Exception('impossible de supprimer le produit');
    }
}
