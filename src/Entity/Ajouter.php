<?php

namespace App\Entity;

use App\Repository\AjouterRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AjouterRepository::class)]
class Ajouter
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $jours_travailler = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_entre_employer = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date_sortie_employer = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date_debut_vacance = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date_fin_vacance = null;

    #[ORM\Column(type: Types::TIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $debut_repas = null;

    #[ORM\Column(type: Types::TIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date_fin_repas = null;

  



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getJoursTravailler(): ?string
    {
        return $this->jours_travailler;
    }

    public function setJoursTravailler(string $jours_travailler): static
    {
        $this->jours_travailler = $jours_travailler;

        return $this;
    }

    public function getDateEntreEmployer(): ?\DateTimeInterface
    {
        return $this->date_entre_employer;
    }

    public function setDateEntreEmployer(\DateTimeInterface $date_entre_employer): static
    {
        $this->date_entre_employer = $date_entre_employer;

        return $this;
    }

    public function getDateSortieEmployer(): ?\DateTimeInterface
    {
        return $this->date_sortie_employer;
    }

    public function setDateSortieEmployer(?\DateTimeInterface $date_sortie_employer): static
    {
        $this->date_sortie_employer = $date_sortie_employer;

        return $this;
    }

    public function getDateDebutVacance(): ?\DateTimeInterface
    {
        return $this->date_debut_vacance;
    }

    public function setDateDebutVacance(?\DateTimeInterface $date_debut_vacance): static
    {
        $this->date_debut_vacance = $date_debut_vacance;

        return $this;
    }

    public function getDateFinVacance(): ?\DateTimeInterface
    {
        return $this->date_fin_vacance;
    }

    public function setDateFinVacance(?\DateTimeInterface $date_fin_vacance): static
    {
        $this->date_fin_vacance = $date_fin_vacance;

        return $this;
    }

    public function getDebutRepas(): ?\DateTimeInterface
    {
        return $this->debut_repas;
    }

    public function setDebutRepas(?\DateTimeInterface $debut_repas): static
    {
        $this->debut_repas = $debut_repas;

        return $this;
    }

    public function getDateFinRepas(): ?\DateTimeInterface
    {
        return $this->date_fin_repas;
    }

    public function setDateFinRepas(?\DateTimeInterface $date_fin_repas): static
    {
        $this->date_fin_repas = $date_fin_repas;

        return $this;
    }



 

  


}
