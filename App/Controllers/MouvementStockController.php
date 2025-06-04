<?php
namespace App\Controllers;

use Core\Contracts\ResourceController;
use Core\Controller;
use App\Services\Implementations\MouvementStockDefault;
use App\Services\Interfaces\MouvementStockService;
use Core\Decorators\Description;
use Core\Decorators\Route;

#[Route('/api/v1/mouvements-stock')]
class MouvementStockController extends Controller implements ResourceController
{
    private MouvementStockService $mouvementStockService;

    public function __construct()
    {
        parent::__construct();
        $this->mouvementStockService = new MouvementStockDefault();
    }

    #[Description("recupere la liste paginee des mouvements de stock")]
    public function index()
    {
        try {
            $this->json($this->mouvementStockService->getMouvementsStocks());
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 404);
        }
    }

    #[Description("affiche les details d un mouvement de stock par son identifiant")]
    public function show($id)
    {
        try {
            return $this->json($this->mouvementStockService->getMouvementStock($id));
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 404);
        }
    }

    #[Description("creer un mouvement de stock")]
    public function store()
    {
        try {
            $data = $this->getJsonInput();
            $mouvementStock = $this->mouvementStockService->createMouvementStock($data);
            return $this->json($mouvementStock, 201);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        }
    }

    #[Description("mettre a jour un mouvement de stock")]
    public function update($id)
    {
        try {
            $data = $this->getJsonInput();
            $mouvementStock = $this->mouvementStockService->updateMouvementStock($id, $data);
            return $this->json($mouvementStock);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        }
    }

    #[Description("supprimer un mouvement de stock")]
    public function destroy($id)
    {
        try {
            $mouvementStock = $this->mouvementStockService->deleteMouvementStock($id);
            return $this->json($mouvementStock);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        }
    }
}
