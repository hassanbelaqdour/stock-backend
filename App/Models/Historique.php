<?php
namespace App\Models;

use JsonSerializable;

class Historique implements JsonSerializable
{
    private ?int $id;
    private string $action;           // type d'action (ex: ajout, suppression, modification)
    private ?int $produitId;          // id du produit concerné (ou autre entité)
    private ?int $utilisateurId;      // id de l'utilisateur ayant fait l'action (optionnel)
    private string $dateAction;       // date/heure de l'action, format ISO8601

    public function __construct(?int $id, string $action, ?int $produitId, ?int $utilisateurId, string $dateAction)
    {
        $this->id = $id;
        $this->action = $action;
        $this->produitId = $produitId;
        $this->utilisateurId = $utilisateurId;
        $this->dateAction = $dateAction;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'action' => $this->action,
            'produit_id' => $this->produitId,
            'utilisateur_id' => $this->utilisateurId,
            'date_action' => $this->dateAction,
        ];
    }

    // getters & setters
    public function getId(): ?int { return $this->id; }
    public function setId(?int $id): void { $this->id = $id; }

    public function getAction(): string { return $this->action; }
    public function setAction(string $action): void { $this->action = $action; }

    public function getProduitId(): ?int { return $this->produitId; }
    public function setProduitId(?int $produitId): void { $this->produitId = $produitId; }

    public function getUtilisateurId(): ?int { return $this->utilisateurId; }
    public function setUtilisateurId(?int $utilisateurId): void { $this->utilisateurId = $utilisateurId; }

    public function getDateAction(): string { return $this->dateAction; }
    public function setDateAction(string $dateAction): void { $this->dateAction = $dateAction; }
}
