<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use \DateTimeInterface;

/**
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
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateCreation;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateDerniereMiseAJour;

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

    public function __construct()
    {
        $this->utilisateursCreateurs = new ArrayCollection();
        $this->utilisateursModificateurs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

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

    public function getDateCreation(): ?DateTimeInterface
    {
        return $this->dateCreation;
    }

    public function setDateCreation(DateTimeInterface $dateCreation): self
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    public function getDateDerniereMiseAJour(): ?DateTimeInterface
    {
        return $this->dateDerniereMiseAJour;
    }

    public function setDateDerniereMiseAJour(?DateTimeInterface $dateDerniereMiseAJour): self
    {
        $this->dateDerniereMiseAJour = $dateDerniereMiseAJour;

        return $this;
    }

    public function getUtilisateurCreation(): ?self
    {
        return $this->utilisateurCreation;
    }

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

    public function addUtilisateursCreateurs(self $utilisateurCreateur): self
    {
        if (!$this->utilisateursCreateurs->contains($utilisateurCreateur)) {
            $this->utilisateursCreateurs[] = $utilisateurCreateur;
            $utilisateurCreateur->setUtilisateurCreation($this);
        }

        return $this;
    }

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

    public function getUtilisateurDerniereMiseAJour(): ?self
    {
        return $this->utilisateurDerniereMiseAJour;
    }

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

    public function addUtilisateurModificateur(self $utilisateurModificateur): self
    {
        if (!$this->utilisateursModificateurs->contains($utilisateurModificateur)) {
            $this->utilisateursModificateurs[] = $utilisateurModificateur;
            $utilisateurModificateur->setUtilisateurDerniereMiseAJour($this);
        }

        return $this;
    }

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
