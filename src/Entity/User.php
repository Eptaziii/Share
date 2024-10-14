<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use ApiPlatform\Metadata\ApiResource;

#[ApiResource()]
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'Erreur lors de la création du compte')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateInscription = null;

    #[ORM\Column(length: 50)]
    private ?string $prenom = null;

    #[ORM\Column(length: 50)]
    private ?string $nom = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Fichier::class)]
    private Collection $fichiers;
    
    #[ORM\ManyToMany(targetEntity: self::class, mappedBy: 'demander')]
    private Collection $usersDemande;
    
    #[ORM\JoinTable(name: "user_demande")]
    #[JoinColumn(name: 'user_id', referencedColumnName: 'id')]
    #[InverseJoinColumn(name: 'demander_id', referencedColumnName: 'id')]
    #[ORM\ManyToMany(targetEntity: self::class, inversedBy: 'usersDemande')]
    private Collection $demander;

    #[ORM\ManyToMany(targetEntity: self::class, inversedBy: 'usersAccepte')]
    #[ORM\JoinTable(
        name: "user_accepter", 
        joinColumns: [new ORM\JoinColumn(name: "user_id", referencedColumnName: "id")], 
        inverseJoinColumns: [new ORM\JoinColumn(name: "accepter_id", referencedColumnName: "id")]
    )]
    private Collection $accepter;

    #[ORM\ManyToMany(targetEntity: self::class, mappedBy: 'accepter')]
    private Collection $usersAccepte;

    public function __construct()
    {
        $this->fichiers = new ArrayCollection();
        $this->demander = new ArrayCollection();
        $this->usersDemande = new ArrayCollection();
        $this->accepter = new ArrayCollection();
        $this->usersAccepte = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
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

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getDateInscription(): ?\DateTimeInterface
    {
        return $this->dateInscription;
    }

    public function setDateInscription(\DateTimeInterface $dateInscription): static
    {
        $this->dateInscription = $dateInscription;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * @return Collection<int, Fichier>
     */
    public function getFichiers(): Collection
    {
        return $this->fichiers;
    }

    public function addFichier(Fichier $fichier): static
    {
        if (!$this->fichiers->contains($fichier)) {
            $this->fichiers->add($fichier);
            $fichier->setUser($this);
        }

        return $this;
    }

    public function removeFichier(Fichier $fichier): static
    {
        if ($this->fichiers->removeElement($fichier)) {
            // set the owning side to null (unless already changed)
            if ($fichier->getUser() === $this) {
                $fichier->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getDemander(): Collection
    {
        return $this->demander;
    }

    public function addDemander(self $demander): static
    {
        if (!$this->demander->contains($demander)) {
            $this->demander->add($demander);
        }

        return $this;
    }

    public function removeDemander(self $demander): static
    {
        $this->demander->removeElement($demander);

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getUsersDemande(): Collection
    {
        return $this->usersDemande;
    }

    public function addUsersDemande(self $usersDemande): static
    {
        if (!$this->usersDemande->contains($usersDemande)) {
            $this->usersDemande->add($usersDemande);
            $usersDemande->addDemander($this);
        }

        return $this;
    }

    public function removeUsersDemande(self $usersDemande): static
    {
        if ($this->usersDemande->removeElement($usersDemande)) {
            $usersDemande->removeDemander($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getAccepter(): Collection
    {
        return $this->accepter;
    }

    public function addAccepter(self $accepter): static
    {
        if (!$this->accepter->contains($accepter)) {
            $this->accepter->add($accepter);
        }

        return $this;
    }

    public function removeAccepter(self $accepter): static
    {
        $this->accepter->removeElement($accepter);

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getUsersAccepte(): Collection
    {
        return $this->usersAccepte;
    }

    public function addUsersAccepte(self $usersAccepte): static
    {
        if (!$this->usersAccepte->contains($usersAccepte)) {
            $this->usersAccepte->add($usersAccepte);
            $usersAccepte->addAccepter($this);
        }

        return $this;
    }

    public function removeUsersAccepte(self $usersAccepte): static
    {
        if ($this->usersAccepte->removeElement($usersAccepte)) {
            $usersAccepte->removeAccepter($this);
        }

        return $this;
    }
}
