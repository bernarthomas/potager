<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use \DateTimeInterface;

/**
 * Class Historique
 * @package App\Entity
 * 
 * @ORM\Entity(repositoryClass="App\Repository\HistoriqueRepository")
 */
class Historique
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;
    
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Action", inversedBy="historique")
     * @ORM\JoinColumn(nullable=false)
     */
    private $action;
    
    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Entite", inversedBy="historique")
     * @ORM\JoinColumn(nullable=false)
     */
    private $entite;

    /**
     * @ORM\Column(type="integer")
     */
    private $occurenceId;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Utilisateur", inversedBy="historique")
     * @ORM\JoinColumn(nullable=false)
     */
    private $utilisateur;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $valeursModifiees = [];

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Action|null
     */
    public function getAction(): ?Action
    {
        return $this->action;
    }

    /**
     * @param Action|null $action
     * 
     * @return $this
     */
    public function setAction(?Action $action): self
    {
        $this->action = $action;

        return $this;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getDate(): ?DateTimeInterface
    {
        return $this->date;
    }

    /**
     * @param  $date
     * 
     * @return $this
     */
    public function setDate( $date): self
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @return Entite|null
     */
    public function getEntite(): ?Entite
    {
        return $this->entite;
    }

    /**
     * @param Entite|null $entite
     * 
     * @return $this
     */
    public function setEntite(?Entite $entite): self
    {
        $this->entite = $entite;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getOccurenceId(): ?int
    {
        return $this->occurenceId;
    }

    /**
     * @param int $occurenceId
     *
     * @return $this
     */
    public function setOccurenceId(int $occurenceId): self
    {
        $this->occurenceId = $occurenceId;

        return $this;
    }

    /**
     * @return Utilisateur|null
     */
    public function getUtilisateur(): ?Utilisateur
    {
        return $this->utilisateur;
    }

    /**
     * @param Utilisateur|null $utilisateur
     * 
     * @return $this
     */
    public function setUtilisateur(?Utilisateur $utilisateur): self
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }

    /**
     * @return array|null
     */
    public function getValeursModifiees(): ?array
    {
        return $this->valeursModifiees;
    }

    /**
     * @param array|null $valeursModifiees
     * 
     * @return $this
     */
    public function setValeursModifiees(?array $valeursModifiees): self
    {
        $this->valeursModifiees = $valeursModifiees;

        return $this;
    }
}