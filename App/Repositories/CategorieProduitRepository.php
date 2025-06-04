<?php
namespace App\Repositories;

use App\Models\CategorieProduit; // Assurez-vous que ce modèle existe
use Core\Facades\RepositoryMutations; // Assurez-vous que cette classe existe
use PDO; // Nécessaire car vos méthodes utilisent PDO
use Exception; // Nécessaire si vos méthodes lancent des exceptions

// Le Repository pour interagir avec la table 'categorie_produit' en base de données.
class CategorieProduitRepository extends RepositoryMutations
{
    /**
     * Constructeur du Repository.
     * Définit le nom de la table gérée par ce repository.
     */
    public function __construct()
    {
        // --- CORRECTION : Passer le nom de table SINGULIER qui existe en base de données ---
        parent::__construct('categorie_produit'); // <-- Nom de table corrigé au singulier
    }

    /**
     * Récupère toutes les catégories de produit de la base de données.
     *
     * @return array Un tableau d'objets CategorieProduit.
     */
    public function findAllCategoriesProduit(): array
    {
        // La requête utilise $this->tableName, qui est maintenant 'categorie_produit'.
        $data = $this->db->getPdo()->query("SELECT * FROM $this->tableName")->fetchAll(PDO::FETCH_ASSOC);
        // Assurez-vous que $this->arrayMapper() existe et mappe correctement les données en objets CategorieProduit.
        return $this->arrayMapper($data);
    }

    /**
     * Recherche une catégorie de produit par son identifiant.
     *
     * @param int $id L'identifiant de la catégorie.
     * @return CategorieProduit L'objet CategorieProduit trouvé.
     * @throws Exception Si la catégorie avec l'ID donné n'est pas trouvée.
     */
    public function findById(int $id): CategorieProduit
    {
        // La requête utilise $this->tableName, qui est maintenant 'categorie_produit'.
        $stmt = $this->db->getPdo()->prepare("SELECT * FROM $this->tableName WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$data) {
            // Lève une exception si la catégorie n'est pas trouvée. Le contrôleur doit gérer cette exception.
            throw new Exception("categorie produit avec id $id introuvable");
        }

        // Assurez-vous que $this->mapper() existe et mappe correctement les données en un objet CategorieProduit.
        return $this->mapper($data);
    }

    /**
     * Mappe un tableau de données provenant de la base de données vers un objet CategorieProduit.
     *
     * @param array $data Tableau associatif des données de la catégorie.
     * @return CategorieProduit L'objet CategorieProduit mappé.
     */
    public function mapper(array $data): CategorieProduit
    {
        // Assurez-vous que la méthode get() existe (probablement héritée ou dans un trait/mixin).
        // Assurez-vous que le modèle CategorieProduit accepte ces arguments dans son constructeur.
        return new CategorieProduit(
            $this->get($data, 'id'),
            $this->get($data, 'nom'),
            $this->get($data, 'description')
        );
    }

    // Vous héritez de save, update, delete (et potentiellement lastInsertId si elle est dans RepositoryMutations)
    // depuis RepositoryMutations.
    // Ces méthodes utiliseront également $this->tableName, donc la correction du constructeur est suffisante.

     /**
     * Méthode utilitaire pour mapper un tableau de tableaux de données en un tableau d'objets.
     * Cette méthode est probablement dans RepositoryMutations ou une classe parente.
     * Si elle n'est pas héritée, vous devrez l'implémenter ici ou dans un endroit partagé.
     *
     * @param array $data Tableau de tableaux associatifs.
     * @return array Tableau d'objets CategorieProduit.
     */
    // protected function arrayMapper(array $data): array
    // {
    //     $mapped = [];
    //     foreach ($data as $row) {
    //         $mapped[] = $this->mapper($row);
    //     }
    //     return $mapped;
    // }

     /**
     * Méthode utilitaire pour récupérer une clé d'un tableau avec une valeur par défaut.
     * Cette méthode est probablement dans RepositoryMutations ou une classe parente.
     * Si elle n'est pas héritée, vous devrez l'implémenter ici ou dans un endroit partagé.
     *
     * @param array $data Tableau dans lequel chercher la clé.
     * @param string $key La clé à rechercher.
     * @param mixed $default La valeur à retourner si la clé n'existe pas.
     * @return mixed La valeur associée à la clé ou la valeur par défaut.
     */
    // protected function get(array $data, string $key, $default = null)
    // {
    //     return $data[$key] ?? $default;
    // }
}