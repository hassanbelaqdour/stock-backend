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

    public function __construct()
    {
        parent::__construct();
        $this->categorieProduitService = new CategorieProduitDefault();
    }

    #[Description("recupere la liste paginee des categories de produit")]
    public function index()
    {
        try {
            $this->json($this->categorieProduitService->getCategoriesProduit());
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 404);
        }
    }

    #[Description("affiche les details d une categorie de produit par son identifiant")]
    public function show($id)
    {
        try {
            return $this->json($this->categorieProduitService->getCategorieProduit($id));
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 404);
        }
    }

    #[Description("creer une categorie de produit")]
    public function store()
    {
        try {
            $data = $this->getJsonInput();
            $categorie = $this->categorieProduitService->createCategorieProduit($data);
            return $this->json($categorie, 201);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        }
    }

    #[Description("mettre a jour une categorie de produit")]
    public function update($id)
    {
        try {
            $data = $this->getJsonInput();
            $categorie = $this->categorieProduitService->updateCategorieProduit($id, $data);
            return $this->json($categorie);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        }
    }

    #[Description("supprimer une categorie de produit")]
    public function destroy($id)
    {
        try {
            $categorie = $this->categorieProduitService->deleteCategorieProduit($id);
            return $this->json($categorie);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        }
    }
}
