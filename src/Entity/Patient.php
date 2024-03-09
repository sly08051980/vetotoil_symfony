<?php

namespace App\Entity;

use App\Repository\PatientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: PatientRepository::class)]
#[Vich\Uploadable]
class Patient
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

  

    #[ORM\Column(length: 255)]
    private ?string $adresse_patient = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $complement_adresse_patient = null;

    #[ORM\Column(length: 5)]
    private ?string $code_postal_patient = null;

    #[ORM\Column(length: 50)]
    private ?string $ville_patient = null;

    #[ORM\Column(length: 10)]
    private ?string $telephone_patient = null;

    #[Vich\UploadableField(mapping: 'product_images', fileNameProperty: 'imageName')]
    private ?File $imageFile = null;

    #[ORM\Column(nullable: true)]
    private ?string $imageName = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_creation_patient = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date_fin_patient = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?User $user = null;

    #[ORM\OneToMany(targetEntity: Animal::class, mappedBy: 'patient')]
    private Collection $animals;

    public function __construct()
    {
        $this->animals = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }


    public function getAdressePatient(): ?string
    {
        return $this->adresse_patient;
    }

    public function setAdressePatient(string $adresse_patient): static
    {
        $this->adresse_patient = $adresse_patient;

        return $this;
    }

    public function getComplementAdressePatient(): ?string
    {
        return $this->complement_adresse_patient;
    }

    public function setComplementAdressePatient(?string $complement_adresse_patient): static
    {
        $this->complement_adresse_patient = $complement_adresse_patient;

        return $this;
    }

    public function getCodePostalPatient(): ?string
    {
        return $this->code_postal_patient;
    }

    public function setCodePostalPatient(string $code_postal_patient): static
    {
        $this->code_postal_patient = $code_postal_patient;

        return $this;
    }

    public function getVillePatient(): ?string
    {
        return $this->ville_patient;
    }

    public function setVillePatient(string $ville_patient): static
    {
        $this->ville_patient = $ville_patient;

        return $this;
    }

    public function getTelephonePatient(): ?string
    {
        return $this->telephone_patient;
    }

    public function setTelephonePatient(string $telephone_patient): static
    {
        $this->telephone_patient = $telephone_patient;

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

    public function setImageName(?string $imageName): void
    {
        $this->imageName = $imageName;
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
    }


    public function getDateCreationPatient(): ?\DateTimeInterface
    {
        return $this->date_creation_patient;
    }

    public function setDateCreationPatient(\DateTimeInterface $date_creation_patient): static
    {
        $this->date_creation_patient = $date_creation_patient;

        return $this;
    }

    public function getDateFinPatient(): ?\DateTimeInterface
    {
        return $this->date_fin_patient;
    }

    public function setDateFinPatient(?\DateTimeInterface $date_fin_patient): static
    {
        $this->date_fin_patient = $date_fin_patient;

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
     * @return Collection<int, Animal>
     */
    public function getAnimals(): Collection
    {
        return $this->animals;
    }

    public function addAnimal(Animal $animal): static
    {
        if (!$this->animals->contains($animal)) {
            $this->animals->add($animal);
            $animal->setPatient($this);
        }

        return $this;
    }

    public function removeAnimal(Animal $animal): static
    {
        if ($this->animals->removeElement($animal)) {
            // set the owning side to null (unless already changed)
            if ($animal->getPatient() === $this) {
                $animal->setPatient(null);
            }
        }

        return $this;
    }
}
