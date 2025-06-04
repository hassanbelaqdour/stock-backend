<?php
namespace App\Repositories;

use App\Models\ProduitLiquide;
use Core\Facades\RepositoryMutations;
use PDO;
use Exception;

class ProduitLiquideRepository extends RepositoryMutations
{
    public function __construct()
    {
        parent::__construct('produits_liquides');
    }

    public function findAllProduitsLiquides(): array
    {
        $data = $this->db->getPdo()->query("SELECT * FROM $this->tableName")->fetchAll(PDO::FETCH_ASSOC);
        return $this->arrayMapper($data);
    }

    public function findById(int $id): ProduitLiquide
    {
        $stmt = $this->db->getPdo()->prepare("SELECT * FROM $this->tableName WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$data) {
            throw new Exception("produit liquide avec id $id introuvable");
        }
        return $this->mapper($data);
    }

    public function mapper(array $data): ProduitLiquide
    {
        return new ProduitLiquide(
            $this->get($data, 'id'),
            $this->get($data, 'nom'),
            (float)$this->get($data, 'quantite'),
            $this->get($data, 'unite'),
            $this->get($data, 'categorie_produit_id')
        );
    }
}
