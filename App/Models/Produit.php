<?php
namespace App\Models;

use JsonSerializable;

class Produit implements JsonSerializable
{
    private ?int $id;
    private string $nom;
    private ?string $description;
    private int $categorieId;
    private int $stock;
    private float $prix;

    public function __construct(?int $id, string $nom, ?string $description, int $categorieId, int $stock, float $prix)
    {
        $this->id = $id;
        $this->nom = $nom;
        $this->description = $description;
        $this->categorieId = $categorieId;
        $this->stock = $stock;
        $this->prix = $prix;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'nom' => $this->nom,
            'description' => $this->description,
            'categorie_id' => $this->categorieId,
            'stock' => $this->stock,
            'prix' => $this->prix,
        ];
    }

    // Getters et setters

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getNom(): string
    {
        return $this->nom;
    }

    public function setNom(string $nom): void
    {
        $this->nom = $nom;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getCategorieId(): int
    {
        return $this->categorieId;
    }

    public function setCategorieId(int $categorieId): void
    {
        $this->categorieId = $categorieId;
    }

    public function getStock(): int
    {
        return $this->stock;
    }

    public function setStock(int $stock): void
    {
        $this->stock = $stock;
    }

    public function getPrix(): float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): void
    {
        $this->prix = $prix;
    }
}
