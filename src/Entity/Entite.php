<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Entite
 * @package App\Entity
 *
 * @ORM\Entity(repositoryClass="App\Repository\EntiteRepository")
 */
class Entite
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Historique", mappedBy="entite")
     */
    private $historique;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $libelle;

    /**
     * Entite constructor.
     */
    public function __construct()
    {
        $this->historique = new ArrayCollection();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|Historique[]
     */
    public function getHistorique(): Collection
    {
        return $this->historique;
    }

    /**
     * @param Historique $historique
     *
     * @return $this
     */
    public function addHistorique(Historique $historique): self
    {
        if (!$this->historique->contains($historique)) {
            $this->historique[] = $historique;
            $historique->setEntite($this);
        }

        return $this;
    }

    /**
     * @param Historique $historique
     *
     * @return $this
     */
    public function removeHistorique(Historique $historique): self
    {
        if ($this->historique->contains($historique)) {
            $this->historique->removeElement($historique);
            // set the owning side to null (unless already changed)
            if ($historique->getEntite() === $this) {
                $historique->setEntite(null);
            }
        }

        return $this;
    }

    /**
     * @return string|null
     */
    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    /**
     * @param string $libelle
     *
     * @return $this
     */
    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }
}