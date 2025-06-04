<?php
namespace App\Repositories;

use App\Models\Produit;
use Core\Facades\RepositoryMutations;
use PDO;
use Exception;

class ProduitRepository extends RepositoryMutations
{
    public function __construct()
    {
        parent::__construct('produits');
    }

    public function findAllProduits(): array
    {
        $data = $this->db->getPdo()->query("SELECT * FROM $this->tableName")->fetchAll(PDO::FETCH_ASSOC);
        return $this->arrayMapper($data);
    }

    public function findById(int $id): Produit
    {
        $stmt = $this->db->getPdo()->prepare("SELECT * FROM $this->tableName WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$data) {
            throw new Exception("produit avec id $id introuvable");
        }
        return $this->mapper($data);
    }

    public function mapper(array $data): Produit
    {
        return new Produit(
            $this->get($data, 'id'),
            $this->get($data, 'nom'),
            $this->get($data, 'description'),
            (int)$this->get($data, 'categorie_id'),
            (int)$this->get($data, 'stock'),
            (float)$this->get($data, 'prix')
        );
    }
}
