<?php
namespace App\Controllers;

use Core\Contracts\ResourceController;
use Core\Controller;
use App\Services\Implementations\FournisseurDefault;
use App\Services\Interfaces\FournisseurService;
use Core\Decorators\Description; // Assurez-vous que cette classe existe dans ce namespace
use Core\Decorators\Route;       // Assurez-vous que cette classe existe dans ce namespace

// Attribut pour définir le préfixe de l'URI pour toutes les routes de ce contrôleur
#[Route('/api/v1/fournisseurs')]
class FournisseurController extends Controller implements ResourceController
{
    // Déclaration de la propriété pour le service Fournisseur
    private FournisseurService $fournisseurService;

    /**
     * Constructeur du contrôleur.
     * Initialise les dépendances (ici, le service Fournisseur).
     */
    public function __construct()
    {
        // Appelle le constructeur de la classe parente Core\Controller
        parent::__construct();

        // Initialisation du service Fournisseur.
        // NOTE : L'utilisation de l'injection de dépendances via le constructeur
        // est la méthode recommandée pour une meilleure testabilité et flexibilité.
        // Exemple: public function __construct(FournisseurService $fournisseurService) { $this->fournisseurService = $fournisseurService; }
        $this->fournisseurService = new FournisseurDefault();
    }

    /**
     * Récupère la liste de tous les fournisseurs (ou paginée si le service l'implémente).
     * Gère la requête GET sur l'URI de base : /api/v1/fournisseurs
     */
    #[Route('', method: 'GET')] // Route pour la racine du contrôleur ('') avec la méthode GET
    #[Description("recupere la liste des fournisseurs")] // Description pour la documentation
    public function index()
    {
        try {
            // Appelle la méthode correspondante dans le service pour obtenir les données
            $fournisseurs = $this->fournisseurService->getFournisseurs();

            // Retourne la réponse au format JSON avec le statut HTTP 200 (par défaut)
            $this->json($fournisseurs);

        } catch (\Exception $e) {
            // En cas d'erreur, capture l'exception et retourne un message d'erreur avec un statut approprié.
            // 500 Internal Server Error est souvent utilisé pour les erreurs non gérées par l'application elle-même (ex: erreur DB).
            return $this->json(['error' => 'Erreur lors de la récupération des fournisseurs: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Affiche les détails d'un fournisseur spécifique par son identifiant.
     * Gère la requête GET sur l'URI : /api/v1/fournisseurs/{id}
     *
     * @param int $id L'identifiant du fournisseur. Le paramètre {id} dans la route est passé à cette variable.
     */
    #[Route('/{id}', method: 'GET')] // Route avec un paramètre dynamique {id} pour la méthode GET
    #[Description("affiche les details d un fournisseur par son identifiant")]
    public function show($id)
    {
        try {
            // Appelle la méthode du service pour récupérer un fournisseur par ID
            $fournisseur = $this->fournisseurService->getFournisseur($id);

            // Vérifie si le service a trouvé un fournisseur
            if (!$fournisseur) {
                 // Si non trouvé, retourne une réponse 404 Not Found
                 return $this->json(['error' => "Fournisseur avec id $id introuvable"], 404);
            }

            // Si trouvé, retourne la réponse JSON avec le fournisseur (statut 200 OK par défaut)
            return $this->json($fournisseur);

        } catch (\Exception $e) {
             // En cas d'erreur (ex: DB), retourne un message d'erreur avec un statut approprié.
             // Si le service lance une exception spécifique avec un message clair pour 404 ou 400,
             // vous pourriez l'intercepter ici et retourner le statut correspondant.
            if (str_contains($e->getMessage(), 'introuvable') || str_contains($e->getMessage(), 'non trouvé')) {
                 return $this->json(['error' => $e->getMessage()], 404); // Si le message indique non trouvé
            }
            return $this->json(['error' => 'Erreur lors de la récupération du fournisseur: ' . $e->getMessage()], 500); // Autres erreurs
        }
    }

    /**
     * Crée un nouveau fournisseur avec les données fournies.
     * Gère la requête POST sur l'URI de base : /api/v1/fournisseurs
     */
    #[Route('', method: 'POST')] // Route pour la racine du contrôleur ('') avec la méthode POST
    #[Description("creer un fournisseur")]
    public function store()
    {
        try {
            // Récupère et décode les données JSON envoyées dans le corps de la requête
            $data = $this->getJsonInput();

            // --- Validation des données d'entrée ---
            // C'est crucial pour la sécurité et la fiabilité. Vérifiez les champs requis et leur format.
            if (empty($data) || !is_array($data)) {
                 return $this->json(['error' => 'Données d\'entrée manquantes ou invalides (doivent être JSON).'], 400);
            }
             if (!isset($data['nom']) || empty(trim($data['nom']))) {
                 return $this->json(['error' => 'Le nom du fournisseur est requis.'], 400);
            }
            // Ajoutez ici des validations pour email, telephone, adresse si elles ont des contraintes spécifiques.


            // Appelle la méthode du service pour créer le fournisseur.
            // Le service gère l'insertion en base de données et retourne l'objet Fournisseur créé (avec son ID).
            $fournisseur = $this->fournisseurService->createFournisseur($data);

            // Retourne la réponse JSON avec l'objet créé et le statut 201 Created
            return $this->json($fournisseur, 201);

        } catch (\InvalidArgumentException $e) {
             // Cette exception est gérée ici si getJsonInput lance une exception pour JSON invalide
             // ou si votre validation des données lance une InvalidArgumentException.
             return $this->json(['error' => $e->getMessage()], 400); // 400 Bad Request pour données client invalides

        } catch (\Exception $e) {
            // Gère toutes les autres exceptions (ex: erreur lors de l'insertion en DB dans le service/repository)
            return $this->json(['error' => 'Erreur lors de la création du fournisseur: ' . $e->getMessage()], 500); // 500 Internal Server Error
        }
    }

    /**
     * Met à jour un fournisseur existant par son identifiant.
     * Gère la requête PUT sur l'URI : /api/v1/fournisseurs/{id}
     *
     * @param int $id L'identifiant du fournisseur à mettre à jour.
     */
    #[Route('/{id}', method: 'PUT')] // Route avec paramètre {id} pour la méthode PUT
    #[Description("mettre a jour un fournisseur")]
    public function update($id)
    {
         try {
            // Récupère et décode les données JSON pour la mise à jour
            $data = $this->getJsonInput();

            // --- Validation des données d'entrée ---
            if (empty($data) || !is_array($data)) {
                 return $this->json(['error' => 'Données de mise à jour manquantes ou invalides (doivent être JSON).'], 400);
            }
             // Vous pourriez vérifier ici que $data contient au moins un champ de mise à jour valide
             $validFields = ['nom', 'email', 'telephone', 'adresse'];
             if (count(array_intersect_key($data, array_flip($validFields))) === 0) {
                 return $this->json(['error' => 'Aucun champ valide fourni pour la mise à jour.'], 400);
             }


            // Appelle la méthode du service pour mettre à jour le fournisseur.
            // Le service est censé gérer l'ID non trouvé et la logique de mise à jour.
            $fournisseur = $this->fournisseurService->updateFournisseur($id, $data);

            // Si le service retourne l'objet Fournisseur mis à jour, c'est un succès (200 OK par défaut)
            return $this->json($fournisseur);

        } catch (\InvalidArgumentException $e) {
             // Gère les exceptions liées aux données d'entrée invalides
             return $this->json(['error' => $e->getMessage()], 400); // 400 Bad Request

        } catch (\Exception $e) {
            // Gère les autres exceptions, y compris si le service/repository signale un ID non trouvé.
             if (str_contains($e->getMessage(), 'introuvable') || str_contains($e->getMessage(), 'non trouvé')) {
                 return $this->json(['error' => $e->getMessage()], 404); // 404 Not Found si le message indique non trouvé
            }
            // Pour les autres erreurs (ex: erreur DB pendant l'update)
            return $this->json(['error' => 'Erreur lors de la mise à jour du fournisseur: ' . $e->getMessage()], 500); // 500 Internal Server Error
        }
    }

    /**
     * Supprime un fournisseur existant par son identifiant.
     * Gère la requête DELETE sur l'URI : /api/v1/fournisseurs/{id}
     *
     * @param int $id L'identifiant du fournisseur à supprimer.
     */
    #[Route('/{id}', method: 'DELETE')] // Route avec paramètre {id} pour la méthode DELETE
    #[Description("supprimer un fournisseur")]
    public function destroy($id)
    {
        try {
             // Appelle la méthode du service pour supprimer le fournisseur.
             // Le service est censé gérer l'ID non trouvé et la logique de suppression.
             // Il retourne l'objet Fournisseur qui a été supprimé (ou lance une exception).
            $fournisseurSupprime = $this->fournisseurService->deleteFournisseur($id);

            // Retourne l'objet qui vient d'être supprimé en JSON (statut 200 OK par défaut)
            // C'est une pratique courante mais alternativement, un 204 No Content est aussi valide pour une suppression réussie.
            return $this->json($fournisseurSupprime); // 200 OK


        } catch (\Exception $e) {
            // Gère les exceptions, y compris si le service/repository signale un ID non trouvé.
             if (str_contains($e->getMessage(), 'introuvable') || str_contains($e->getMessage(), 'non trouvé')) {
                 return $this->json(['error' => $e->getMessage()], 404); // 404 Not Found si le message indique non trouvé
            }
            // Pour les autres erreurs (ex: erreur DB pendant la suppression)
            return $this->json(['error' => 'Erreur lors de la suppression du fournisseur: ' . $e->getMessage()], 500); // 500 Internal Server Error
        }
    }

    // Note : Puisque ce contrôleur implémente ResourceController, il s'attend à avoir
    // les méthodes index, show, store, update, destroy définies, ce qui est bien le cas.
}