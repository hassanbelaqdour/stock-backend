<?php
namespace App\Controllers;

use Core\Contracts\ResourceController;
use Core\Controller;
use App\Services\Implementations\CategorieProduitDefault; 
use App\Services\Interfaces\CategorieProduitService; 
use Core\Decorators\Description; 
use Core\Decorators\Route;       



#[Route('/api/v1/categories_produit')]
class CategorieProduitController extends Controller implements ResourceController
{
    
    private CategorieProduitService $categorieProduitService;

    /**
     * Constructeur du contrôleur.
     * Initialise les dépendances (ici, le service CatégorieProduit).
     */
    public function __construct()
    {
        
        parent::__construct();

        
        
        
        $this->categorieProduitService = new CategorieProduitDefault(); 
    }

    /**
     * Gère la requête GET pour lister les catégories de produit.
     * Route: GET /api/v1/categories_produit
     */
    
    #[Route('', method: 'GET')] 
    #[Description("recupere la liste paginee des categories de produit")] 
    public function index()
    {
        try {
            
            $categories = $this->categorieProduitService->getCategoriesProduit(); 

            
            $this->json($categories);

        } catch (\Exception $e) {
            
            
            return $this->json(['error' => 'Erreur lors de la récupération des categories de produit: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Gère la requête GET pour afficher une catégorie spécifique.
     * Route: GET /api/v1/categories_produit/{id}
     * Le paramètre dynamique {id} de l'URI est passé à la méthode $id.
     *
     * @param int $id L'identifiant du fournisseur.
     */
    
    #[Route('/{id}', method: 'GET')] 
    #[Description("affiche les details d une categorie de produit par son identifiant")]
    public function show($id)
    {
        try {
            
            $categorie = $this->categorieProduitService->getCategorieProduit($id); 

            
            if (!$categorie) {
                 
                 return $this->json(['error' => "Categorie avec id $id introuvable"], 404);
            }

            
            return $this->json($categorie);

        } catch (\Exception $e) {
             
             
             if (str_contains($e->getMessage(), 'introuvable') || str_contains($e->getMessage(), 'non trouvé')) {
                 return $this->json(['error' => $e->getMessage()], 404); 
            }
            
            return $this->json(['error' => 'Erreur lors de la récupération de la categorie: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Gère la requête POST pour créer une nouvelle catégorie.
     * Route: POST /api/v1/categories_produit
     * Les données sont attendues dans le corps de la requête (JSON).
     */
    
    #[Route('', method: 'POST')] 
    #[Description("creer une categorie de produit")]
    public function store()
    {
        try {
            
            $data = $this->getJsonInput();

            
            if (empty($data) || !is_array($data)) {
                 return $this->json(['error' => 'Données d\'entrée manquantes ou invalides (doivent être JSON).'], 400); 
            }
             if (!isset($data['nom']) || empty(trim($data['nom']))) {
                 return $this->json(['error' => 'Le nom de la categorie est requis et ne peut pas être vide.'], 400); 
            }
            


            
            $categorie = $this->categorieProduitService->createCategorieProduit($data); 

            
            return $this->json($categorie, 201); 

        } catch (\InvalidArgumentException $e) {
             
             return $this->json(['error' => $e->getMessage()], 400); 

        } catch (\Exception $e) {
            
            return $this->json(['error' => 'Erreur lors de la création de la categorie: ' . $e->getMessage()], 500); 
        }
    }

    /**
     * Gère la requête PUT pour mettre à jour une catégorie existante.
     * Route: PUT /api/v1/categories_produit/{id}
     * Les données de mise à jour sont attendues dans le corps de la requête (JSON).
     *
     * @param int $id L'identifiant de la catégorie à mettre à jour.
     */
    
    #[Route('/{id}', method: 'PUT')] 
    #[Description("mettre a jour une categorie de produit")]
    public function update($id)
    {
         try {
            
            $data = $this->getJsonInput();

            
             if (empty($data) || !is_array($data)) {
                 return $this->json(['error' => 'Données de mise à jour manquantes ou invalides (doivent être JSON).'], 400); 
             }
             
             $validFieldsForUpdate = ['nom', 'description']; 
             if (count(array_intersect_key($data, array_flip($validFieldsForUpdate))) === 0) {
                  return $this->json(['error' => 'Aucun champ valide fourni pour la mise à jour.'], 400); 
             }
             


            
            $categorie = $this->categorieProduitService->updateCategorieProduit($id, $data); 

            
            return $this->json($categorie);

        } catch (\InvalidArgumentException $e) {
             
             return $this->json(['error' => $e->getMessage()], 400); 

        } catch (\Exception $e) {
            
             
             if (str_contains($e->getMessage(), 'introuvable') || str_contains($e->getMessage(), 'non trouvé')) {
                 return $this->json(['error' => $e->getMessage()], 404); 
            }
            
            return $this->json(['error' => 'Erreur lors de la mise à jour de la categorie: ' . $e->getMessage()], 500); 
        }
    }

    /**
     * Gère la requête DELETE pour supprimer une catégorie.
     * Route: DELETE /api/v1/categories_produit/{id}
     *
     * @param int $id L'identifiant de la catégorie à supprimer.
     */
    
    #[Route('/{id}', method: 'DELETE')] 
    #[Description("Supprimer une categorie de produit")]
    public function destroy($id)
    {
        try {
             
            $categorieSupprimee = $this->categorieProduitService->deleteCategorieProduit($id); 

            
            
            
            return $this->json($categorieSupprimee); 


        } catch (\Exception $e) {
            
             if (str_contains($e->getMessage(), 'introuvable') || str_contains($e->getMessage(), 'non trouvé')) {
                 return $this->json(['error' => $e->getMessage()], 404); 
            }
            return $this->json(['error' => 'Erreur lors de la suppression de la categorie: ' . $e->getMessage()], 500); 
        }
    }

    
    
}