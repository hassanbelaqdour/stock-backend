<?php
namespace App\Models;

use JsonSerializable;

class CategorieProduit implements JsonSerializable
{
    private ?int $id;
    private string $nom;
    private ?string $description;

    public function __construct(?int $id, string $nom, ?string $description)
    {
        $this->id = $id;
        $this->nom = $nom;
        $this->description = $description;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'nom' => $this->nom,
            'description' => $this->description,
        ];
    }

    // getters & setters

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
}
