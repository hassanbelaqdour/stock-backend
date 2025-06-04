<?php
namespace App\Services\Implementations;

use App\Repositories\CategorieProduitRepository;
use App\Services\Interfaces\CategorieProduitService;
use App\Models\CategorieProduit;
use Exception;

class CategorieProduitDefault implements CategorieProduitService
{
    private CategorieProduitRepository $categorieProduitRepository;

    public function __construct()
    {
        $this->categorieProduitRepository = new CategorieProduitRepository();
    }

    public function getCategorieProduit(int $id): CategorieProduit
    {
        return $this->categorieProduitRepository->findById($id);
    }

    public function getCategoriesProduit(): array
    {
        return $this->categorieProduitRepository->findAllCategoriesProduit();
    }

    public function createCategorieProduit(array $data): CategorieProduit
    {
        $categorie = new CategorieProduit(
            null,
            $data['nom'],
            $data['description'] ?? null
        );

        $insertData = [
            'nom' => $categorie->getNom(),
            'description' => $categorie->getDescription(),
        ];

        if ($this->categorieProduitRepository->save($insertData)) {
            $lastId = $this->categorieProduitRepository->lastInsertId();
            $categorie->setId($lastId);
            return $categorie;
        }

        throw new Exception('impossible de creer la categorie');
    }

    public function updateCategorieProduit(int $id, array $data): CategorieProduit
    {
        $categorie = $this->categorieProduitRepository->findById($id);
        $updateData = [];

        if (isset($data['nom'])) {
            $categorie->setNom($data['nom']);
            $updateData['nom'] = $data['nom'];
        }
        if (array_key_exists('description', $data)) {
            $categorie->setDescription($data['description']);
            $updateData['description'] = $data['description'];
        }

        if (empty($updateData)) {
            throw new Exception('aucune donnee valide pour la mise a jour');
        }

        $this->categorieProduitRepository->update($updateData, ['id' => $id]);

        return $this->categorieProduitRepository->findById($id);
    }

    public function deleteCategorieProduit(int $id): CategorieProduit
    {
        $categorie = $this->categorieProduitRepository->findById($id);
        if ($this->categorieProduitRepository->delete(['id' => $id])) {
            return $categorie;
        }
        throw new Exception('impossible de supprimer la categorie');
    }
}
