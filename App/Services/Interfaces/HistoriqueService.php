<?php
namespace App\Services\Interfaces;

use App\Models\Historique;

interface HistoriqueService
{
    public function getHistorique(int $id): Historique;

    public function getHistoriques(): array;

    public function createHistorique(array $data): Historique;

    public function updateHistorique(int $id, array $data): Historique;

    public function deleteHistorique(int $id): Historique;
}
