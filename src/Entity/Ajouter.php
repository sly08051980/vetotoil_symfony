<?php

namespace App\Entity;

use App\Repository\AjouterRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
    private ?\DateTimeInterface $date_entree_employer = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date_sortie_employer = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date_debut__vacance = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date_fin_vacance = null;

    #[ORM\Column(type: Types::TIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $debut_repas = null;

    #[ORM\Column(type: Types::TIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $fin_repas = null;

    #[ORM\ManyToMany(targetEntity: Societe::class, inversedBy: 'ajouters')]
    private Collection $societe;

    public function __construct()
    {
        $this->societe = new ArrayCollection();
    }

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

    public function getDateEntreeEmployer(): ?\DateTimeInterface
    {
        return $this->date_entree_employer;
    }

    public function setDateEntreeEmployer(\DateTimeInterface $date_entree_employer): static
    {
        $this->date_entree_employer = $date_entree_employer;

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
        return $this->date_debut__vacance;
    }

    public function setDateDebutVacance(?\DateTimeInterface $date_debut__vacance): static
    {
        $this->date_debut__vacance = $date_debut__vacance;

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

    public function getFinRepas(): ?\DateTimeInterface
    {
        return $this->fin_repas;
    }

    public function setFinRepas(?\DateTimeInterface $fin_repas): static
    {
        $this->fin_repas = $fin_repas;

        return $this;
    }

    /**
     * @return Collection<int, Societe>
     */
    public function getSociete(): Collection
    {
        return $this->societe;
    }

    public function addSociete(Societe $societe): static
    {
        if (!$this->societe->contains($societe)) {
            $this->societe->add($societe);
        }

        return $this;
    }

    public function removeSociete(Societe $societe): static
    {
        $this->societe->removeElement($societe);

        return $this;
    }
}
