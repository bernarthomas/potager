<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use \DateTimeInterface;

/**
 * Class Recolte
 * @package App\Entity
 * 
 * @ORM\Entity(repositoryClass="App\Repository\RecolteRepository")
 */
class Recolte
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $commentaire;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Culture", inversedBy="recoltes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $culture;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\Column(type="float")
     */
    private $poids;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    /**
     * @param string|null $commentaire
     * 
     * @return $this
     */
    public function setCommentaire(?string $commentaire): self
    {
        $this->commentaire = $commentaire;

        return $this;
    }

    /**
     * @return Culture|null
     */
    public function getCulture(): ?Culture
    {
        return $this->culture;
    }

    /**
     * @param Culture|null $culture
     * 
     * @return $this
     */
    public function setCulture(?Culture $culture): self
    {
        $this->culture = $culture;

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
     * @param DateTimeInterface $date
     * 
     * @return $this
     */
    public function setDate(DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @return float|null
     */
    public function getPoids(): ?float
    {
        return $this->poids;
    }

    /**
     * @param float $poids
     * 
     * @return $this
     */
    public function setPoids(float $poids): self
    {
        $this->poids = $poids;

        return $this;
    }
}