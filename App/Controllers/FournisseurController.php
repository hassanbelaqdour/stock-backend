<?php
namespace App\Controllers;

use Core\Contracts\ResourceController;
use Core\Controller;
use App\Services\Implementations\FournisseurDefault;
use App\Services\Interfaces\FournisseurService;
use Core\Decorators\Description;
use Core\Decorators\Route;

#[Route('/api/v1/fournisseurs')]
class FournisseurController extends Controller implements ResourceController
{
    private FournisseurService $fournisseurService;

    public function __construct()
    {
        parent::__construct();
        $this->fournisseurService = new FournisseurDefault();
    }

    #[Description("recupere la liste paginee des fournisseurs")]
    public function index()
    {
        try {
            $this->json($this->fournisseurService->getFournisseurs());
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 404);
        }
    }

    #[Description("affiche les details d un fournisseur par son identifiant")]
    public function show($id)
    {
        try {
            return $this->json($this->fournisseurService->getFournisseur($id));
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 404);
        }
    }

    #[Description("creer un fournisseur")]
    public function store()
    {
        try {
            $data = $this->getJsonInput();
            $fournisseur = $this->fournisseurService->createFournisseur($data);
            return $this->json($fournisseur, 201);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        }
    }

    #[Description("mettre a jour un fournisseur")]
    public function update($id)
    {
        try {
            $data = $this->getJsonInput();
            $fournisseur = $this->fournisseurService->updateFournisseur($id, $data);
            return $this->json($fournisseur);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        }
    }

    #[Description("supprimer un fournisseur")]
    public function destroy($id)
    {
        try {
            $fournisseur = $this->fournisseurService->deleteFournisseur($id);
            return $this->json($fournisseur);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        }
    }
}
