<?php

namespace App\Entity;

use App\Repository\EmployerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EmployerRepository::class)]
class Employer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $adresse_employer = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $complement_adresse_employer = null;

    #[ORM\Column(length: 5)]
    private ?string $code_postal_employer = null;

    #[ORM\Column(length: 50)]
    private ?string $ville_employer = null;

    #[ORM\Column(length: 10)]
    private ?string $telephone_employer = null;

    #[ORM\Column(length: 20)]
    private ?string $profession_employer = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $images = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_creation_employer = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?User $user = null;

    #[ORM\OneToMany(targetEntity: Ajouter::class, mappedBy: 'employer')]
    private Collection $ajouters;

    public function __construct()
    {
        $this->ajouters = new ArrayCollection();
    }



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAdresseEmployer(): ?string
    {
        return $this->adresse_employer;
    }

    public function setAdresseEmployer(string $adresse_employer): static
    {
        $this->adresse_employer = $adresse_employer;

        return $this;
    }

    public function getComplementAdresseEmployer(): ?string
    {
        return $this->complement_adresse_employer;
    }

    public function setComplementAdresseEmployer(?string $complement_adresse_employer): static
    {
        $this->complement_adresse_employer = $complement_adresse_employer;

        return $this;
    }

    public function getCodePostalEmployer(): ?string
    {
        return $this->code_postal_employer;
    }

    public function setCodePostalEmployer(string $code_postal_employer): static
    {
        $this->code_postal_employer = $code_postal_employer;

        return $this;
    }

    public function getVilleEmployer(): ?string
    {
        return $this->ville_employer;
    }

    public function setVilleEmployer(string $ville_employer): static
    {
        $this->ville_employer = $ville_employer;

        return $this;
    }

    public function getTelephoneEmployer(): ?string
    {
        return $this->telephone_employer;
    }

    public function setTelephoneEmployer(string $telephone_employer): static
    {
        $this->telephone_employer = $telephone_employer;

        return $this;
    }

    public function getProfessionEmployer(): ?string
    {
        return $this->profession_employer;
    }

    public function setProfessionEmployer(string $profession_employer): static
    {
        $this->profession_employer = $profession_employer;

        return $this;
    }

    public function getImages(): ?string
    {
        return $this->images;
    }

    public function setImages(?string $images): static
    {
        $this->images = $images;

        return $this;
    }

    public function getDateCreationEmployer(): ?\DateTimeInterface
    {
        return $this->date_creation_employer;
    }

    public function setDateCreationEmployer(\DateTimeInterface $date_creation_employer): static
    {
        $this->date_creation_employer = $date_creation_employer;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, Ajouter>
     */
    public function getAjouters(): Collection
    {
        return $this->ajouters;
    }

    public function addAjouter(Ajouter $ajouter): static
    {
        if (!$this->ajouters->contains($ajouter)) {
            $this->ajouters->add($ajouter);
            $ajouter->setEmployer($this);
        }

        return $this;
    }

    public function removeAjouter(Ajouter $ajouter): static
    {
        if ($this->ajouters->removeElement($ajouter)) {
            // set the owning side to null (unless already changed)
            if ($ajouter->getEmployer() === $this) {
                $ajouter->setEmployer(null);
            }
        }

        return $this;
    }




}
