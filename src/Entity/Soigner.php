<?php

namespace App\Entity;

use App\Repository\SoignerRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SoignerRepository::class)]
class Soigner
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $acte_soins = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $traitement = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $nombre_fois = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date_soins = null;

    #[ORM\ManyToOne(inversedBy: 'soigners')]
    private ?Societe $societe = null;

    #[ORM\ManyToOne(inversedBy: 'soigners')]
    private ?Employer $employer = null;

    #[ORM\ManyToOne(inversedBy: 'soigners')]
    private ?Patient $patient = null;

    #[ORM\ManyToOne(inversedBy: 'soigners')]
    private ?Animal $animal = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getActeSoins(): ?string
    {
        return $this->acte_soins;
    }

    public function setActeSoins(?string $acte_soins): static
    {
        $this->acte_soins = $acte_soins;

        return $this;
    }

    public function getTraitement(): ?string
    {
        return $this->traitement;
    }

    public function setTraitement(?string $traitement): static
    {
        $this->traitement = $traitement;

        return $this;
    }

    public function getNombreFois(): ?string
    {
        return $this->nombre_fois;
    }

    public function setNombreFois(?string $nombre_fois): static
    {
        $this->nombre_fois = $nombre_fois;

        return $this;
    }

    public function getDateSoins(): ?\DateTimeInterface
    {
        return $this->date_soins;
    }

    public function setDateSoins(?\DateTimeInterface $date_soins): static
    {
        $this->date_soins = $date_soins;

        return $this;
    }

    public function getSociete(): ?Societe
    {
        return $this->societe;
    }

    public function setSociete(?Societe $societe): static
    {
        $this->societe = $societe;

        return $this;
    }

    public function getEmployer(): ?Employer
    {
        return $this->employer;
    }

    public function setEmployer(?Employer $employer): static
    {
        $this->employer = $employer;

        return $this;
    }

    public function getPatient(): ?Patient
    {
        return $this->patient;
    }

    public function setPatient(?Patient $patient): static
    {
        $this->patient = $patient;

        return $this;
    }

    public function getAnimal(): ?Animal
    {
        return $this->animal;
    }

    public function setAnimal(?Animal $animal): static
    {
        $this->animal = $animal;

        return $this;
    }
}
