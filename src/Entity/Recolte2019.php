<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Recolte2019
 * @package App\Entity
 *
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

    /**
     * @return string|null
     */
    public function getCulture(): ?string
    {
        return $this->culture;
    }

    /**
     * @param string $culture
     *
     * @return $this
     */
    public function setCulture(string $culture): self
    {
        $this->culture = $culture;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getMois(): ?string
    {
        return $this->mois;
    }

    /**
     * @param string $mois
     *
     * @return $this
     */
    public function setMois(string $mois): self
    {
        $this->mois = $mois;

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
     * @param float|null $poids
     *
     * @return $this
     */
    public function setPoids(?float $poids): self
    {
        $this->poids = $poids;

        return $this;
    }
}
