<?php

namespace App\Entity;

use App\Repository\PartageRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PartageRepository::class)]
class Partage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'partages')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $userSource = null;

    #[ORM\ManyToOne(inversedBy: 'partages')]
    private ?User $userTarget = null;

    #[ORM\ManyToOne(inversedBy: 'partages')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Fichier $fichier = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserSource(): ?User
    {
        return $this->userSource;
    }

    public function setUserSource(?User $userSource): static
    {
        $this->userSource = $userSource;

        return $this;
    }

    public function getUserTarget(): ?User
    {
        return $this->userTarget;
    }

    public function setUserTarget(?User $userTarget): static
    {
        $this->userTarget = $userTarget;

        return $this;
    }

    public function getFichier(): ?Fichier
    {
        return $this->fichier;
    }

    public function setFichier(?Fichier $fichier): static
    {
        $this->fichier = $fichier;

        return $this;
    }
}
