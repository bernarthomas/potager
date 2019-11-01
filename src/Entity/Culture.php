<?php

namespace App\Entity;

use App\Interfaces\EntiteInterface;
use App\Interfaces\Historisable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Culture
 * @package App\Entity
 *
 * @ORM\Entity(repositoryClass="App\Repository\CultureRepository")
 */
class Culture implements Historisable
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

    /**
     * Culture constructor.
     */
    public function __construct()
    {
        $this->recoltes = new ArrayCollection();
    }

    /**
     * @return array
     */
    public function toArray() : array
    {
        return ['id' => $this->getId(), 'libelle' => $this->getLibelle()];
    }

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

    /**
     * @return Collection|Recolte[]
     */
    public function getRecoltes(): Collection
    {
        return $this->recoltes;
    }

    /**
     * @param Recolte $recolte
     *
     * @return $this
     */
    public function addRecolte(Recolte $recolte): self
    {
        if (!$this->recoltes->contains($recolte)) {
            $this->recoltes[] = $recolte;
            $recolte->setCulture($this);
        }

        return $this;
    }

    /**
     * @param Recolte $recolte
     *
     * @return $this
     */
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