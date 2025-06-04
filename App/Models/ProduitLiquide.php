<?php
namespace App\Models;

use JsonSerializable;

class ProduitLiquide implements JsonSerializable
{
    private ?int $id;
    private string $nom;
    private float $quantite; // quantité en litres ou unité appropriée
    private ?string $unite;  // unité (ex: litre, ml, etc.)
    private ?int $categorieProduitId;

    public function __construct(?int $id, string $nom, float $quantite, ?string $unite, ?int $categorieProduitId)
    {
        $this->id = $id;
        $this->nom = $nom;
        $this->quantite = $quantite;
        $this->unite = $unite;
        $this->categorieProduitId = $categorieProduitId;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'nom' => $this->nom,
            'quantite' => $this->quantite,
            'unite' => $this->unite,
            'categorie_produit_id' => $this->categorieProduitId,
        ];
    }

    // getters & setters
    public function getId(): ?int { return $this->id; }
    public function setId(?int $id): void { $this->id = $id; }

    public function getNom(): string { return $this->nom; }
    public function setNom(string $nom): void { $this->nom = $nom; }

    public function getQuantite(): float { return $this->quantite; }
    public function setQuantite(float $quantite): void { $this->quantite = $quantite; }

    public function getUnite(): ?string { return $this->unite; }
    public function setUnite(?string $unite): void { $this->unite = $unite; }

    public function getCategorieProduitId(): ?int { return $this->categorieProduitId; }
    public function setCategorieProduitId(?int $id): void { $this->categorieProduitId = $id; }
}
