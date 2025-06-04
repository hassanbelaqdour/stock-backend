<?php
namespace App\Controllers;

use Core\Contracts\ResourceController;
use Core\Controller;
use App\Services\Implementations\ProduitDefault;
use App\Services\Interfaces\ProduitService;
use Core\Decorators\Description;
use Core\Decorators\Route;

#[Route('/api/v1/produits')]
class ProduitController extends Controller implements ResourceController
{
    private ProduitService $produitService;

    public function __construct()
    {
        parent::__construct();
        $this->produitService = new ProduitDefault();
    }

    #[Description("recupere la liste paginee des produits")]
    public function index()
    {
        try {
            $this->json($this->produitService->getProduits());
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 404);
        }
    }

    #[Description("affiche les details d un produit par son identifiant")]
    public function show($id)
    {
        try {
            return $this->json($this->produitService->getProduit($id));
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 404);
        }
    }

    #[Description("creer un produit")]
    public function store()
    {
        try {
            $data = $this->getJsonInput();
            $produit = $this->produitService->createProduit($data);
            return $this->json($produit, 201);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        }
    }

    #[Description("mettre a jour un produit")]
    public function update($id)
    {
        try {
            $data = $this->getJsonInput();
            $produit = $this->produitService->updateProduit($id, $data);
            return $this->json($produit);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        }
    }

    #[Description("supprimer un produit")]
    public function destroy($id)
    {
        try {
            $produit = $this->produitService->deleteProduit($id);
            return $this->json($produit);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        }
    }
}
