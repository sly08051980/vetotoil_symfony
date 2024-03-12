<?php

namespace App\Entity;

use App\Repository\EmployerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Uid\UuidV7;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: EmployerRepository::class)]
#[Vich\Uploadable]
class Employer
{
    #[ORM\Id]
    #[ORM\GeneratedValue('CUSTOM')]
    #[ORM\Column(type: 'uuid',unique:true)]
    #[ORM\CustomIdGenerator('doctrine.uuid_generator')]
    private ?UuidV7 $id = null;

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



    #[Vich\UploadableField(mapping: 'employer_images', fileNameProperty: 'imageEmployer')]
    private ?File $imageFile = null;

    #[ORM\Column(nullable: true)]
    private ?string $imageEmployer = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_creation_employer = null;

    #[ORM\OneToOne(targetEntity: User::class, inversedBy: 'employer', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(name: "user_id", referencedColumnName: "id", nullable: false)]
    
    private ?User $user = null;

    #[ORM\OneToMany(targetEntity: Ajouter::class, mappedBy: 'employer')]
    private Collection $ajouters;

    #[ORM\OneToMany(targetEntity: Rdv::class, mappedBy: 'employer')]
    private Collection $rdvs;

    public function __construct()
    {
        $this->ajouters = new ArrayCollection();
        $this->rdvs = new ArrayCollection();
    }







    public function getId(): ?UuidV7
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


     /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $imageFile
     */
    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;

        if (null !== $imageFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setImageEmployer(?string $imageEmployer): void
    {
        $this->imageEmployer = $imageEmployer;
    }

    public function getImageEmployer(): ?string
    {
        return $this->imageEmployer;
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

    /**
     * @return Collection<int, Rdv>
     */
    public function getRdvs(): Collection
    {
        return $this->rdvs;
    }

    public function addRdv(Rdv $rdv): static
    {
        if (!$this->rdvs->contains($rdv)) {
            $this->rdvs->add($rdv);
            $rdv->setEmployer($this);
        }

        return $this;
    }

    public function removeRdv(Rdv $rdv): static
    {
        if ($this->rdvs->removeElement($rdv)) {
            // set the owning side to null (unless already changed)
            if ($rdv->getEmployer() === $this) {
                $rdv->setEmployer(null);
            }
        }

        return $this;
    }



  




}
