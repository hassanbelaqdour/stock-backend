<?php
namespace App\Models;

use JsonSerializable;

class MouvementStock implements JsonSerializable
{
    private ?int $id;
    private ?int $produitId;
    private int $quantite;
    private string $type;  // 'entree' ou 'sortie'
    private string $dateMouvement; // format ISO8601

    public function __construct(?int $id, ?int $produitId, int $quantite, string $type, string $dateMouvement)
    {
        $this->id = $id;
        $this->produitId = $produitId;
        $this->quantite = $quantite;
        $this->type = $type;
        $this->dateMouvement = $dateMouvement;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'produit_id' => $this->produitId,
            'quantite' => $this->quantite,
            'type' => $this->type,
            'date_mouvement' => $this->dateMouvement,
        ];
    }

    // getters & setters
    public function getId(): ?int { return $this->id; }
    public function setId(?int $id): void { $this->id = $id; }

    public function getProduitId(): ?int { return $this->produitId; }
    public function setProduitId(?int $produitId): void { $this->produitId = $produitId; }

    public function getQuantite(): int { return $this->quantite; }
    public function setQuantite(int $quantite): void { $this->quantite = $quantite; }

    public function getType(): string { return $this->type; }
    public function setType(string $type): void { $this->type = $type; }

    public function getDateMouvement(): string { return $this->dateMouvement; }
    public function setDateMouvement(string $dateMouvement): void { $this->dateMouvement = $dateMouvement; }
}
