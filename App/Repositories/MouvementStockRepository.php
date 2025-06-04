<?php
namespace App\Repositories;

use App\Models\MouvementStock;
use Core\Facades\RepositoryMutations;
use PDO;
use Exception;

class MouvementStockRepository extends RepositoryMutations
{
    public function __construct()
    {
        parent::__construct('mouvements_stock');
    }

    public function findAllMouvementsStocks(): array
    {
        $data = $this->db->getPdo()->query("SELECT * FROM $this->tableName")->fetchAll(PDO::FETCH_ASSOC);
        return $this->arrayMapper($data);
    }

    public function findById(int $id): MouvementStock
    {
        $stmt = $this->db->getPdo()->prepare("SELECT * FROM $this->tableName WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$data) {
            throw new Exception("mouvement stock avec id $id introuvable");
        }
        return $this->mapper($data);
    }

    public function mapper(array $data): MouvementStock
    {
        return new MouvementStock(
            $this->get($data, 'id'),
            $this->get($data, 'produit_id'),
            (int)$this->get($data, 'quantite'),
            $this->get($data, 'type'),
            $this->get($data, 'date_mouvement')
        );
    }
}
