<?php
namespace App\Repositories;

use App\Models\Historique;
use Core\Facades\RepositoryMutations;
use PDO;
use Exception;

class HistoriqueRepository extends RepositoryMutations
{
    public function __construct()
    {
        parent::__construct('historiques');
    }

    public function findAllHistoriques(): array
    {
        $data = $this->db->getPdo()->query("SELECT * FROM $this->tableName")->fetchAll(PDO::FETCH_ASSOC);
        return $this->arrayMapper($data);
    }

    public function findById(int $id): Historique
    {
        $stmt = $this->db->getPdo()->prepare("SELECT * FROM $this->tableName WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$data) {
            throw new Exception("historique avec id $id introuvable");
        }
        return $this->mapper($data);
    }

    public function mapper(array $data): Historique
    {
        return new Historique(
            $this->get($data, 'id'),
            $this->get($data, 'action'),
            $this->get($data, 'produit_id'),
            $this->get($data, 'utilisateur_id'),
            $this->get($data, 'date_action')
        );
    }
}
