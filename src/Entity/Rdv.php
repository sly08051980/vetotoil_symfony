<?php

namespace App\Entity;

use App\Repository\RdvRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RdvRepository::class)]
class Rdv
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_rdv = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $heure_rdv = null;

    #[ORM\Column(length: 10, nullable: true)]
    private ?string $status_rdv = null;

    #[ORM\ManyToOne(inversedBy: 'rdvs')]
    private ?Societe $societe = null;

    #[ORM\ManyToOne(inversedBy: 'rdvs')]
    private ?Animal $animal = null;

    #[ORM\ManyToOne(inversedBy: 'rdvs')]
    private ?Employer $employer = null;

    #[ORM\ManyToOne(inversedBy: 'rdvs')]
    private ?Patient $patient = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateRdv(): ?\DateTimeInterface
    {
        return $this->date_rdv;
    }

    public function setDateRdv(\DateTimeInterface $date_rdv): static
    {
        $this->date_rdv = $date_rdv;

        return $this;
    }

    public function getHeureRdv(): ?\DateTimeInterface
    {
        return $this->heure_rdv;
    }

    public function setHeureRdv(\DateTimeInterface $heure_rdv): static
    {
        $this->heure_rdv = $heure_rdv;

        return $this;
    }

    public function getStatusRdv(): ?string
    {
        return $this->status_rdv;
    }

    public function setStatusRdv(?string $status_rdv): static
    {
        $this->status_rdv = $status_rdv;

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

    public function getAnimal(): ?Animal
    {
        return $this->animal;
    }

    public function setAnimal(?Animal $animal): static
    {
        $this->animal = $animal;

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
}
