<?php

namespace App\Entity;

use App\Repository\AnimalRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Uid\UuidV7;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: AnimalRepository::class)]
#[Vich\Uploadable]
class Animal
{
    #[ORM\Id]
    #[ORM\GeneratedValue('CUSTOM')]
    #[ORM\Column(type: 'uuid',unique:true)]
    #[ORM\CustomIdGenerator('doctrine.uuid_generator')]
    private ?UuidV7 $id = null;

    #[ORM\Column(length: 50)]
    private ?string $prenom_animal = null;
    // #[ORM\ManyToOne(targetEntity: Patient::class, inversedBy: 'animals')]
    // #[ORM\JoinColumn(name: "patient_id", referencedColumnName: "id", nullable: false)]
    // private ?Patient $patient = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date_naissance_animal = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_creation_animal = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date_fin_animal = null;

    #[Vich\UploadableField(mapping: 'animal_images', fileNameProperty: 'imageAnimal')]
    private ?File $imageFile = null;

    #[ORM\Column(nullable: true)]
    private ?string $imageAnimal = null;

    #[ORM\ManyToOne(inversedBy: 'animals')]
    private ?Race $race = null;

    #[ORM\ManyToOne(inversedBy: 'animals')]
    private ?User $user = null;

    #[ORM\OneToMany(targetEntity: Rdv::class, mappedBy: 'animal')]
    private Collection $rdvs;

    #[ORM\OneToMany(targetEntity: Soigner::class, mappedBy: 'animal')]
    private Collection $soigners;

    public function __construct()
    {
        $this->rdvs = new ArrayCollection();
        $this->soigners = new ArrayCollection();
    }



    public function getId(): ?UuidV7
    {
        return $this->id;
    }

    public function getPrenomAnimal(): ?string
    {
        return $this->prenom_animal;
    }

    public function setPrenomAnimal(string $prenom_animal): static
    {
        $this->prenom_animal = $prenom_animal;

        return $this;
    }

    public function getDateNaissanceAnimal(): ?\DateTimeInterface
    {
        return $this->date_naissance_animal;
    }

    public function setDateNaissanceAnimal(?\DateTimeInterface $date_naissance_animal): static
    {
        $this->date_naissance_animal = $date_naissance_animal;

        return $this;
    }

    public function getDateCreationAnimal(): ?\DateTimeInterface
    {
        return $this->date_creation_animal;
    }

    public function setDateCreationAnimal(\DateTimeInterface $date_creation_animal): static
    {
        $this->date_creation_animal = $date_creation_animal;

        return $this;
    }

    public function getDateFinAnimal(): ?\DateTimeInterface
    {
        return $this->date_fin_animal;
    }

    public function setDateFinAnimal(\DateTimeInterface $date_fin_animal=null): self
    {
        $this->date_fin_animal = $date_fin_animal;

        return $this;
    }

    public function getRace(): ?Race
    {
        return $this->race;
    }

    public function setRace(?Race $race): static
    {
        $this->race = $race;

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

    public function setImageAnimal(?string $imageAnimal): void
    {
        $this->imageAnimal = $imageAnimal;
    }

    public function getImageAnimal(): ?string
    {
        return $this->imageAnimal;
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
            $rdv->setAnimal($this);
        }

        return $this;
    }

    public function removeRdv(Rdv $rdv): static
    {
        if ($this->rdvs->removeElement($rdv)) {
            // set the owning side to null (unless already changed)
            if ($rdv->getAnimal() === $this) {
                $rdv->setAnimal(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Soigner>
     */
    public function getSoigners(): Collection
    {
        return $this->soigners;
    }

    public function addSoigner(Soigner $soigner): static
    {
        if (!$this->soigners->contains($soigner)) {
            $this->soigners->add($soigner);
            $soigner->setAnimal($this);
        }

        return $this;
    }

    public function removeSoigner(Soigner $soigner): static
    {
        if ($this->soigners->removeElement($soigner)) {
            // set the owning side to null (unless already changed)
            if ($soigner->getAnimal() === $this) {
                $soigner->setAnimal(null);
            }
        }

        return $this;
    }

}
