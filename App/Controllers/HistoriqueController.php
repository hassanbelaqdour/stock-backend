<?php
namespace App\Controllers;

use Core\Contracts\ResourceController;
use Core\Controller;
use App\Services\Implementations\HistoriqueDefault;
use App\Services\Interfaces\HistoriqueService;
use Core\Decorators\Description;
use Core\Decorators\Route;

#[Route('/api/v1/historiques')]
class HistoriqueController extends Controller implements ResourceController
{
    private HistoriqueService $historiqueService;

    public function __construct()
    {
        parent::__construct();
        $this->historiqueService = new HistoriqueDefault();
    }

    #[Description("recupere la liste paginee des historiques")]
    public function index()
    {
        try {
            $this->json($this->historiqueService->getHistoriques());
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 404);
        }
    }

    #[Description("affiche les details d un historique par son identifiant")]
    public function show($id)
    {
        try {
            return $this->json($this->historiqueService->getHistorique($id));
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 404);
        }
    }

    #[Description("creer un historique")]
    public function store()
    {
        try {
            $data = $this->getJsonInput();
            $historique = $this->historiqueService->createHistorique($data);
            return $this->json($historique, 201);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        }
    }

    #[Description("mettre a jour un historique")]
    public function update($id)
    {
        try {
            $data = $this->getJsonInput();
            $historique = $this->historiqueService->updateHistorique($id, $data);
            return $this->json($historique);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        }
    }

    #[Description("supprimer un historique")]
    public function destroy($id)
    {
        try {
            $historique = $this->historiqueService->deleteHistorique($id);
            return $this->json($historique);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        }
    }
}
