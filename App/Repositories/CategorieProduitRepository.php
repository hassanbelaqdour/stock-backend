<?php
namespace App\Repositories;

use App\Models\CategorieProduit;
use Core\Facades\RepositoryMutations;
use PDO;
use Exception;

class CategorieProduitRepository extends RepositoryMutations
{
    public function __construct()
    {
        parent::__construct('categories_produit');
    }

    public function findAllCategoriesProduit(): array
    {
        $data = $this->db->getPdo()->query("SELECT * FROM $this->tableName")->fetchAll(PDO::FETCH_ASSOC);
        return $this->arrayMapper($data);
    }

    public function findById(int $id): CategorieProduit
    {
        $stmt = $this->db->getPdo()->prepare("SELECT * FROM $this->tableName WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$data) {
            throw new Exception("categorie produit avec id $id introuvable");
        }
        return $this->mapper($data);
    }

    public function mapper(array $data): CategorieProduit
    {
        return new CategorieProduit(
            $this->get($data, 'id'),
            $this->get($data, 'nom'),
            $this->get($data, 'description')
        );
    }
}
