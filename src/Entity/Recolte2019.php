<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Recolte2019Repository")
 * @ORM\Table(name="recolte2019")
 */
class Recolte2019
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $culture;

    /**
     * @ORM\Column(type="text")
     */
    private $mois;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $poids;

//    public function getId(): ?int
//    {
//        return $this->id;
//    }

    public function getCulture(): ?string
    {
        return $this->culture;
    }

    public function setCulture(string $culture): self
    {
        $this->culture = $culture;

        return $this;
    }

    public function getMois(): ?string
    {
        return $this->mois;
    }

    public function setMois(string $mois): self
    {
        $this->mois = $mois;

        return $this;
    }

    public function getPoids(): ?float
    {
        return $this->poids;
    }

    public function setPoids(?float $poids): self
    {
        $this->poids = $poids;

        return $this;
    }
}
