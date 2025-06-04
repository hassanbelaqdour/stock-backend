<?php
namespace App\Services\Implementations;

use App\Repositories\MouvementStockRepository;
use App\Services\Interfaces\MouvementStockService;
use App\Models\MouvementStock;
use Exception;

class MouvementStockDefault implements MouvementStockService
{
    private MouvementStockRepository $mouvementStockRepository;

    public function __construct()
    {
        $this->mouvementStockRepository = new MouvementStockRepository();
    }

    public function getMouvementStock(int $id): MouvementStock
    {
        return $this->mouvementStockRepository->findById($id);
    }

    public function getMouvementsStocks(): array
    {
        return $this->mouvementStockRepository->findAllMouvementsStocks();
    }

    public function createMouvementStock(array $data): MouvementStock
    {
        $mouvementStock = new MouvementStock(
            null,
            $data['produit_id'],
            $data['quantite'],
            $data['type'],
            $data['date_mouvement']
        );

        $insertData = [
            'produit_id' => $mouvementStock->getProduitId(),
            'quantite' => $mouvementStock->getQuantite(),
            'type' => $mouvementStock->getType(),
            'date_mouvement' => $mouvementStock->getDateMouvement(),
        ];

        if ($this->mouvementStockRepository->save($insertData)) {
            $lastId = $this->mouvementStockRepository->lastInsertId();
            $mouvementStock->setId($lastId);
            return $mouvementStock;
        }

        throw new Exception('impossible de creer mouvement stock');
    }

    public function updateMouvementStock(int $id, array $data): MouvementStock
    {
        $mouvementStock = $this->mouvementStockRepository->findById($id);
        $updateData = [];

        if (array_key_exists('produit_id', $data)) {
            $mouvementStock->setProduitId($data['produit_id']);
            $updateData['produit_id'] = $data['produit_id'];
        }
        if (isset($data['quantite'])) {
            $mouvementStock->setQuantite($data['quantite']);
            $updateData['quantite'] = $data['quantite'];
        }
        if (isset($data['type'])) {
            $mouvementStock->setType($data['type']);
            $updateData['type'] = $data['type'];
        }
        if (isset($data['date_mouvement'])) {
            $mouvementStock->setDateMouvement($data['date_mouvement']);
            $updateData['date_mouvement'] = $data['date_mouvement'];
        }

        if (empty($updateData)) {
            throw new Exception('aucune donnee valide pour mise a jour');
        }

        $this->mouvementStockRepository->update($updateData, ['id' => $id]);

        return $this->mouvementStockRepository->findById($id);
    }

    public function deleteMouvementStock(int $id): MouvementStock
    {
        $mouvementStock = $this->mouvementStockRepository->findById($id);
        if ($this->mouvementStockRepository->delete(['id' => $id])) {
            return $mouvementStock;
        }
        throw new Exception('impossible de supprimer mouvement stock');
    }
}
