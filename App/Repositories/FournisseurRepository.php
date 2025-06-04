<?php
namespace App\Repositories;

use App\Models\Fournisseur;
use Core\Facades\RepositoryMutations;
use PDO;
use Exception;

class FournisseurRepository extends RepositoryMutations
{
    public function __construct()
    {
        parent::__construct('fournisseurs');
    }

    public function findAllFournisseurs(): array
    {
        $data = $this->db->getPdo()->query("SELECT * FROM $this->tableName")->fetchAll(PDO::FETCH_ASSOC);
        return $this->arrayMapper($data);
    }

    public function findById(int $id): Fournisseur
    {
        $stmt = $this->db->getPdo()->prepare("SELECT * FROM $this->tableName WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$data) {
            throw new Exception("fournisseur avec id $id introuvable");
        }
        return $this->mapper($data);
    }

    public function mapper(array $data): Fournisseur
    {
        return new Fournisseur(
            $this->get($data, 'id'),
            $this->get($data, 'nom'),
            $this->get($data, 'email'),
            $this->get($data, 'telephone'),
            $this->get($data, 'adresse')
        );
    }
}
