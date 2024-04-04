<?php

namespace App\Entity;

use App\Repository\CommuneRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommuneRepository::class)]
class Commune
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $nom_commune = null;

    #[ORM\Column(length: 5)]
    private ?string $code_postal_commune = null;

    #[ORM\Column(length: 50)]
    private ?string $latitude = null;

    #[ORM\Column(length: 50)]
    private ?string $longitude = null;

    #[ORM\Column(length: 100)]
    private ?string $nom_departement = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomCommune(): ?string
    {
        return $this->nom_commune;
    }

    public function setNomCommune(string $nom_commune): static
    {
        $this->nom_commune = $nom_commune;

        return $this;
    }

    public function getCodePostalCommune(): ?string
    {
        return $this->code_postal_commune;
    }

    public function setCodePostalCommune(string $code_postal_commune): static
    {
        $this->code_postal_commune = $code_postal_commune;

        return $this;
    }

    public function getLatitude(): ?string
    {
        return $this->latitude;
    }

    public function setLatitude(string $latitude): static
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?string
    {
        return $this->longitude;
    }

    public function setLongitude(string $longitude): static
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getNomDepartement(): ?string
    {
        return $this->nom_departement;
    }

    public function setNomDepartement(string $nom_departement): static
    {
        $this->nom_departement = $nom_departement;

        return $this;
    }
}
