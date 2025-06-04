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
        
        
        if (empty($data['nom'])) {
             throw new \InvalidArgumentException('Le nom du fournisseur est requis.');
        }
        
        

        
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

        
        
        $lastId = $this->fournisseurRepository->save($insertData);

        
        
        if ($lastId > 0) {
            
            $fournisseur->setId($lastId);
            
            return $fournisseur;
        } else {
             
             
             
             throw new Exception('Impossible de créer le fournisseur : l\'insertion a échoué.');
        }
    }

    public function updateFournisseur(int $id, array $data): Fournisseur
    {
        
        

        $fournisseur = $this->fournisseurRepository->findById($id);

        
        if (!$fournisseur) {
             
             
             
        }


        $updateData = [];

        
        if (isset($data['nom'])) {
            $updateData['nom'] = $data['nom'];
        }
        if (array_key_exists('email', $data)) { 
            $updateData['email'] = $data['email'];
        }
        if (array_key_exists('telephone', $data)) {
            $updateData['telephone'] = $data['telephone'];
        }
        if (array_key_exists('adresse', $data)) {
            $updateData['adresse'] = $data['adresse'];
        }

        if (empty($updateData)) {
            
            throw new \InvalidArgumentException('Aucune donnée valide fournie pour la mise à jour.');
        }

        
        
        $success = $this->fournisseurRepository->update($updateData, ['id' => $id]);

        if ($success) {
            
            
            
            return $this->fournisseurRepository->findById($id);
        } else {
            
            
            
            
             
             throw new Exception('Échec de la mise à jour du fournisseur.');
        }
    }

    public function deleteFournisseur(int $id): Fournisseur 
    {
        
        
        $fournisseur = $this->fournisseurRepository->findById($id);

        
        
        

        
        
        $success = $this->fournisseurRepository->delete(['id' => $id]);

        if ($success) {
            
            return $fournisseur;
        } else {
            
            
             
            throw new Exception('Échec de la suppression du fournisseur.');
        }
    }

    
    
    
    
    
}