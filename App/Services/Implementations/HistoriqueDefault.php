<?php
namespace App\Services\Implementations;

use App\Repositories\HistoriqueRepository;
use App\Services\Interfaces\HistoriqueService;
use App\Models\Historique;
use Exception;

class HistoriqueDefault implements HistoriqueService
{
    private HistoriqueRepository $historiqueRepository;

    public function __construct()
    {
        $this->historiqueRepository = new HistoriqueRepository();
    }

    public function getHistorique(int $id): Historique
    {
        return $this->historiqueRepository->findById($id);
    }

    public function getHistoriques(): array
    {
        return $this->historiqueRepository->findAllHistoriques();
    }

    public function createHistorique(array $data): Historique
    {
        $historique = new Historique(
            null,
            $data['action'],
            $data['produit_id'] ?? null,
            $data['utilisateur_id'] ?? null,
            $data['date_action']
        );

        $insertData = [
            'action' => $historique->getAction(),
            'produit_id' => $historique->getProduitId(),
            'utilisateur_id' => $historique->getUtilisateurId(),
            'date_action' => $historique->getDateAction(),
        ];

        if ($this->historiqueRepository->save($insertData)) {
            $lastId = $this->historiqueRepository->lastInsertId();
            $historique->setId($lastId);
            return $historique;
        }

        throw new Exception('impossible de creer historique');
    }

    public function updateHistorique(int $id, array $data): Historique
    {
        $historique = $this->historiqueRepository->findById($id);
        $updateData = [];

        if (isset($data['action'])) {
            $historique->setAction($data['action']);
            $updateData['action'] = $data['action'];
        }
        if (array_key_exists('produit_id', $data)) {
            $historique->setProduitId($data['produit_id']);
            $updateData['produit_id'] = $data['produit_id'];
        }
        if (array_key_exists('utilisateur_id', $data)) {
            $historique->setUtilisateurId($data['utilisateur_id']);
            $updateData['utilisateur_id'] = $data['utilisateur_id'];
        }
        if (isset($data['date_action'])) {
            $historique->setDateAction($data['date_action']);
            $updateData['date_action'] = $data['date_action'];
        }

        if (empty($updateData)) {
            throw new Exception('aucune donnee valide pour mise a jour');
        }

        $this->historiqueRepository->update($updateData, ['id' => $id]);

        return $this->historiqueRepository->findById($id);
    }

    public function deleteHistorique(int $id): Historique
    {
        $historique = $this->historiqueRepository->findById($id);
        if ($this->historiqueRepository->delete(['id' => $id])) {
            return $historique;
        }
        throw new Exception('impossible de supprimer historique');
    }
}
