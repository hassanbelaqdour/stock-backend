<?php
namespace App\Controllers;

use Core\Contracts\ResourceController;
use Core\Controller;
use App\Services\Implementations\ProduitLiquideDefault;
use App\Services\Interfaces\ProduitLiquideService;
use Core\Decorators\Description;
use Core\Decorators\Route;

#[Route('/api/v1/produits_liquides')]
class ProduitLiquideController extends Controller implements ResourceController
{
    private ProduitLiquideService $produitLiquideService;

    public function __construct()
    {
        parent::__construct();
        $this->produitLiquideService = new ProduitLiquideDefault();
    }

    #[Description("recupere la liste paginee des produits liquides")]
    public function index()
    {
        try {
            $this->json($this->produitLiquideService->getProduitsLiquides());
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 404);
        }
    }

    #[Description("affiche les details d un produit liquide par son identifiant")]
    public function show($id)
    {
        try {
            return $this->json($this->produitLiquideService->getProduitLiquide($id));
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 404);
        }
    }

    #[Description("creer un produit liquide")]
    public function store()
    {
        try {
            $data = $this->getJsonInput();
            $produit = $this->produitLiquideService->createProduitLiquide($data);
            return $this->json($produit, 201);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        }
    }

    #[Description("mettre a jour un produit liquide")]
    public function update($id)
    {
        try {
            $data = $this->getJsonInput();
            $produit = $this->produitLiquideService->updateProduitLiquide($id, $data);
            return $this->json($produit);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        }
    }

    #[Description("supprimer un produit liquide")]
    public function destroy($id)
    {
        try {
            $produit = $this->produitLiquideService->deleteProduitLiquide($id);
            return $this->json($produit);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        }
    }
}
