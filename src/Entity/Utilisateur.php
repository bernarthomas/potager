<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use \DateTimeInterface;

/**
 * Class Utilisateur
 * @package App\Entity
 *
 * @ORM\Entity(repositoryClass="App\Repository\UtilisateurRepository")
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */
class Utilisateur implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean")
     */
    private $actif;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateCreation;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateDerniereMiseAJour;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Historique", mappedBy="utilisateur")
     */
    private $historique;

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Utilisateur", inversedBy="utilisateursCreateurs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $utilisateurCreation;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Utilisateur", mappedBy="utilisateurCreation")
     */
    private $utilisateursCreateurs;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Utilisateur", inversedBy="utilisateursModificateurs")
     */
    private $utilisateurDerniereMiseAJour;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Utilisateur", mappedBy="utilisateurDerniereMiseAJour")
     */
    private $utilisateursModificateurs;

    /**
     * Utilisateur constructor.
     */
    public function __construct()
    {
        $this->utilisateursCreateurs = new ArrayCollection();
        $this->utilisateursModificateurs = new ArrayCollection();
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
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string $email
     *
     * @return $this
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param array $roles
     *
     * @return $this
     */
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    /**
     * @param string $password
     *
     * @return $this
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getDateCreation(): ?DateTimeInterface
    {
        return $this->dateCreation;
    }

    /**
     * @param DateTimeInterface $dateCreation
     *
     * @return $this
     */
    public function setDateCreation(DateTimeInterface $dateCreation): self
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getDateDerniereMiseAJour(): ?DateTimeInterface
    {
        return $this->dateDerniereMiseAJour;
    }

    /**
     * @param DateTimeInterface|null $dateDerniereMiseAJour
     *
     * @return $this
     */
    public function setDateDerniereMiseAJour(?DateTimeInterface $dateDerniereMiseAJour): self
    {
        $this->dateDerniereMiseAJour = $dateDerniereMiseAJour;

        return $this;
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
            $historique->setUtilisateur($this);
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
            if ($historique->getUtilisateur() === $this) {
                $historique->setUtilisateur(null);
            }
        }

        return $this;
    }

    /**
     * @return $this|null
     */
    public function getUtilisateurCreation(): ?self
    {
        return $this->utilisateurCreation;
    }

    /**
     * @param Utilisateur|null $utilisateurCreation
     *
     * @return $this
     */
    public function setUtilisateurCreation(?self $utilisateurCreation): self
    {
        $this->utilisateurCreation = $utilisateurCreation;

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getUtilisateursCreateurs(): Collection
    {
        return $this->utilisateursCreateurs;
    }

    /**
     * @param Utilisateur $utilisateurCreateur
     *
     * @return $this
     */
    public function addUtilisateursCreateurs(self $utilisateurCreateur): self
    {
        if (!$this->utilisateursCreateurs->contains($utilisateurCreateur)) {
            $this->utilisateursCreateurs[] = $utilisateurCreateur;
            $utilisateurCreateur->setUtilisateurCreation($this);
        }

        return $this;
    }

    /**
     * @param Utilisateur $utilisateurCreateur
     *
     * @return $this
     */
    public function removeUtilisateursCreateur(self $utilisateurCreateur): self
    {
        if ($this->utilisateursCreateurs->contains($utilisateurCreateur)) {
            $this->utilisateursCreateurs->removeElement($utilisateurCreateur);
            // set the owning side to null (unless already changed)
            if ($utilisateurCreateur->getUtilisateurCreation() === $this) {
                $utilisateurCreateur->setUtilisateurCreation(null);
            }
        }

        return $this;
    }

    /**
     * @return $this|null
     */
    public function getUtilisateurDerniereMiseAJour(): ?self
    {
        return $this->utilisateurDerniereMiseAJour;
    }

    /**
     * @param Utilisateur|null $tilisateurDerniereMiseAJour
     *
     * @return $this
     */
    public function setUtilisateurDerniereMiseAJour(?self $tilisateurDerniereMiseAJour): self
    {
        $this->utilisateurDerniereMiseAJour = $tilisateurDerniereMiseAJour;

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getUtilisateursModificateurs(): Collection
    {
        return $this->utilisateursModificateurs;
    }

    /**
     * @param Utilisateur $utilisateurModificateur
     *
     * @return $this
     */
    public function addUtilisateurModificateur(self $utilisateurModificateur): self
    {
        if (!$this->utilisateursModificateurs->contains($utilisateurModificateur)) {
            $this->utilisateursModificateurs[] = $utilisateurModificateur;
            $utilisateurModificateur->setUtilisateurDerniereMiseAJour($this);
        }

        return $this;
    }

    /**
     * @param Utilisateur $utilisateurModificateur
     *
     * @return $this
     */
    public function removeUtilisateurModificateur(self $utilisateurModificateur): self
    {
        if ($this->utilisateursModificateurs->contains($utilisateurModificateur)) {
            $this->utilisateursModificateurs->removeElement($utilisateurModificateur);
            // set the owning side to null (unless already changed)
            if ($utilisateurModificateur->getUtilisateurDerniereMiseAJour() === $this) {
                $utilisateurModificateur->setUtilisateurDerniereMiseAJour(null);
            }
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getActif()
    {
        return $this->actif;
    }

    /**
     * @param mixed $actif
     *
     * @return Utilisateur
     */
    public function setActif($actif)
    {
        $this->actif = $actif;

        return $this;
    }
}
