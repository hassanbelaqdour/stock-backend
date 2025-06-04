<?php
namespace App\Models;

use JsonSerializable;

class Fournisseur implements JsonSerializable
{
    private ?int $id;
    private string $nom;
    private ?string $email;
    private ?string $telephone;
    private ?string $adresse;

    public function __construct(?int $id, string $nom, ?string $email, ?string $telephone, ?string $adresse)
    {
        $this->id = $id;
        $this->nom = $nom;
        $this->email = $email;
        $this->telephone = $telephone;
        $this->adresse = $adresse;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'nom' => $this->nom,
            'email' => $this->email,
            'telephone' => $this->telephone,
            'adresse' => $this->adresse,
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
    public function getEmail(): ?string
    {
        return $this->email;
    }
    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }
    public function getTelephone(): ?string
    {
        return $this->telephone;
    }
    public function setTelephone(?string $telephone): void
    {
        $this->telephone = $telephone;
    }
    public function getAdresse(): ?string
    {
        return $this->adresse;
    }
    public function setAdresse(?string $adresse): void
    {
        $this->adresse = $adresse;
    }
}
