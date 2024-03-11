<?php

namespace App\Entity;

use App\Repository\SocieteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: SocieteRepository::class)]
#[Vich\Uploadable]
class Societe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 14)]
    private ?string $siret = null;
    
    #[ORM\Column(length: 50)]
    private ?string $nom_societe = null;

    #[ORM\Column(length: 20)]
    private ?string $profession_societe = null;

  

    #[ORM\Column(length: 255)]
    private ?string $adresse_societe = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $complement_adresse_societe = null;

    #[ORM\Column(length: 5)]
    private ?string $code_postal_societe = null;

    #[ORM\Column(length: 50)]
    private ?string $ville_societe = null;

    #[ORM\Column(length: 10)]
    private ?string $telephone_societe = null;

    #[ORM\Column(length: 10)]
    private ?string $telephone_dirigeant = null;

    #[Vich\UploadableField(mapping: 'societe_images', fileNameProperty: 'imageSociete')]
    private ?File $imageFile = null;

    #[ORM\Column(nullable: true)]
    private ?string $imageSociete = null;


    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_creation_societe = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date_resiliation_societe = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date_validation_societe = null;

    #[ORM\OneToOne(targetEntity: User::class, inversedBy: 'societe', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(name: "user_id", referencedColumnName: "id", nullable: false)]
    private ?User $user = null;

    #[ORM\OneToMany(targetEntity: Ajouter::class, mappedBy: 'societe')]
    private Collection $ajouters;

    public function __construct()
    {
        $this->ajouters = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSiret(): ?string
    {
        return $this->siret;
    }

    public function setSiret(string $siret): static
    {
        $this->siret = $siret;

        return $this;
    }
    public function getNomSociete(): ?string
    {
        return $this->nom_societe;
    }

    public function setNomSociete(string $nom_societe): static
    {
        $this->nom_societe = $nom_societe;

        return $this;
    }

    public function getProfessionSociete(): ?string
    {
        return $this->profession_societe;
    }

    public function setProfessionSociete(string $profession_societe): static
    {
        $this->profession_societe = $profession_societe;

        return $this;
    }

   

    public function getAdresseSociete(): ?string
    {
        return $this->adresse_societe;
    }

    public function setAdresseSociete(string $adresse_societe): static
    {
        $this->adresse_societe = $adresse_societe;

        return $this;
    }

    public function getComplementAdresseSociete(): ?string
    {
        return $this->complement_adresse_societe;
    }

    public function setComplementAdresseSociete(?string $complement_adresse_societe): static
    {
        $this->complement_adresse_societe = $complement_adresse_societe;

        return $this;
    }

    public function getCodePostalSociete(): ?string
    {
        return $this->code_postal_societe;
    }

    public function setCodePostalSociete(string $code_postal_societe): static
    {
        $this->code_postal_societe = $code_postal_societe;

        return $this;
    }

    public function getVilleSociete(): ?string
    {
        return $this->ville_societe;
    }

    public function setVilleSociete(string $ville_societe): static
    {
        $this->ville_societe = $ville_societe;

        return $this;
    }

    public function getTelephoneSociete(): ?string
    {
        return $this->telephone_societe;
    }

    public function setTelephoneSociete(string $telephone_societe): static
    {
        $this->telephone_societe = $telephone_societe;

        return $this;
    }

    public function getTelephoneDirigeant(): ?string
    {
        return $this->telephone_dirigeant;
    }

    public function setTelephoneDirigeant(string $telephone_dirigeant): static
    {
        $this->telephone_dirigeant = $telephone_dirigeant;

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

    public function setImageSociete(?string $imageSociete): void
    {
        $this->imageSociete = $imageSociete;
    }

    public function getImageSociete(): ?string
    {
        return $this->imageSociete;
    }



    public function getDateCreationSociete(): ?\DateTimeInterface
    {
        return $this->date_creation_societe;
    }

    public function setDateCreationSociete(\DateTimeInterface $date_creation_societe): static
    {
        $this->date_creation_societe = $date_creation_societe;

        return $this;
    }

    public function getDateResiliationSociete(): ?\DateTimeInterface
    {
        return $this->date_resiliation_societe;
    }

    public function setDateResiliationSociete(?\DateTimeInterface $date_resiliation_societe): static
    {
        $this->date_resiliation_societe = $date_resiliation_societe;

        return $this;
    }

    public function getDateValidationSociete(): ?\DateTimeInterface
    {
        return $this->date_validation_societe;
    }

    public function setDateValidationSociete(?\DateTimeInterface $date_validation_societe): static
    {
        $this->date_validation_societe = $date_validation_societe;

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
            $ajouter->setSociete($this);
        }

        return $this;
    }

    public function removeAjouter(Ajouter $ajouter): static
    {
        if ($this->ajouters->removeElement($ajouter)) {
            // set the owning side to null (unless already changed)
            if ($ajouter->getSociete() === $this) {
                $ajouter->setSociete(null);
            }
        }

        return $this;
    }




}
