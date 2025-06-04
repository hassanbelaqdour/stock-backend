<?php
namespace App\Services\Implementations;

use App\Repositories\FournisseurRepository;
use App\Services\Interfaces\FournisseurService;
use App\Models\Fournisseur;
use Exception;

class FournisseurDefault implements FournisseurService
{
    private FournisseurRepository $fournisseurRepository;

    public function __construct()
    {
        $this->fournisseurRepository = new FournisseurRepository();
    }

    public function getFournisseur(int $id): Fournisseur
    {
        return $this->fournisseurRepository->findById($id);
    }

    public function getFournisseurs(): array
    {
        return $this->fournisseurRepository->findAllFournisseurs();
    }

    public function createFournisseur(array $data): Fournisseur
    {
        $fournisseur = new Fournisseur(
            null,
            $data['nom'],
            $data['email'] ?? null,
            $data['telephone'] ?? null,
            $data['adresse'] ?? null
        );

        $insertData = [
            'nom' => $fournisseur->getNom(),
            'email' => $fournisseur->getEmail(),
            'telephone' => $fournisseur->getTelephone(),
            'adresse' => $fournisseur->getAdresse(),
        ];

        if ($this->fournisseurRepository->save($insertData)) {
            $lastId = $this->fournisseurRepository->lastInsertId();
            $fournisseur->setId($lastId);
            return $fournisseur;
        }

        throw new Exception('impossible de creer le fournisseur');
    }

    public function updateFournisseur(int $id, array $data): Fournisseur
    {
        $fournisseur = $this->fournisseurRepository->findById($id);
        $updateData = [];

        if (isset($data['nom'])) {
            $fournisseur->setNom($data['nom']);
            $updateData['nom'] = $data['nom'];
        }
        if (array_key_exists('email', $data)) {
            $fournisseur->setEmail($data['email']);
            $updateData['email'] = $data['email'];
        }
        if (array_key_exists('telephone', $data)) {
            $fournisseur->setTelephone($data['telephone']);
            $updateData['telephone'] = $data['telephone'];
        }
        if (array_key_exists('adresse', $data)) {
            $fournisseur->setAdresse($data['adresse']);
            $updateData['adresse'] = $data['adresse'];
        }

        if (empty($updateData)) {
            throw new Exception('aucune donnee valide pour la mise a jour');
        }

        $this->fournisseurRepository->update($updateData, ['id' => $id]);

        return $this->fournisseurRepository->findById($id);
    }

    public function deleteFournisseur(int $id): Fournisseur
    {
        $fournisseur = $this->fournisseurRepository->findById($id);
        if ($this->fournisseurRepository->delete(['id' => $id])) {
            return $fournisseur;
        }
        throw new Exception('impossible de supprimer le fournisseur');
    }
}
