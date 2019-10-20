<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CultureRepository")
 */
class Culture
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
    private $libelle;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Recolte", mappedBy="culture")
     */
    private $recoltes;

    public function __construct()
    {
        $this->recoltes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * @return Collection|Recolte[]
     */
    public function getRecoltes(): Collection
    {
        return $this->recoltes;
    }

    public function addRecolte(Recolte $recolte): self
    {
        if (!$this->recoltes->contains($recolte)) {
            $this->recoltes[] = $recolte;
            $recolte->setCulture($this);
        }

        return $this;
    }

    public function removeRecolte(Recolte $recolte): self
    {
        if ($this->recoltes->contains($recolte)) {
            $this->recoltes->removeElement($recolte);
            // set the owning side to null (unless already changed)
            if ($recolte->getCulture() === $this) {
                $recolte->setCulture(null);
            }
        }

        return $this;
    }
}
