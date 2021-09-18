<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\RangeFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Repository\AnnonceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=AnnonceRepository::class)
 */
#[ApiResource(
    collectionOperations: [
        'get' => ['normalization_context' => ['groups' => 'annonce:get:collection']],
        'post',
    ],
    itemOperations: ['get', 'put', 'delete', 'patch'],
    normalizationContext: ['groups' => 'annonce:get']
)]
#[ApiFilter(SearchFilter::class, properties:['typeCarburant.libelle' => 'exact', 'marque.nom' => 'exact', 'modele.nom' => 'exact', 'modele.marque.nom' => 'exact'])]
#[ApiFilter(RangeFilter::class, properties: ['kilometrage', 'anneeCirculation', 'prix'])]
class Annonce
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    #[Groups([
        'annonce:get',
        'annonce:get:collection',
        'modele:get',
        'carburant:get',
        'garage:get'
    ])]
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotNull(
     *     message="Ce champ est obligatoire"
     * )
     * @Assert\Length(
     *     min = 8,
     *     max= 255,
     *     minMessage = "Ce champ doit faire 8 charactères minimum",
     *     maxMessage="Ce champ ne peut pas exceder 255 charactères"
     * )
     */
    #[Groups([
        'annonce:get',
        'annonce:get:collection',
        'modele:get',
        'carburant:get',
        'garage:get'
    ])]
    private $titre;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotNull(
     *     message="Ce champ est obligatoire"
     * )
     * @Assert\Length(
     *     min = 30,
     *     max= 4500,
     *     minMessage = "Ce champ doit faire 30 charactères minimum",
     *     maxMessage="Ce champ ne peut pas exceder 4500 charactères"
     * )
     */
    #[Groups([
        'annonce:get',
        'annonce:get:collection'
    ])]
    private $description;

    /**
     * @ORM\Column(type="decimal", precision=7, scale=2)
     * @Assert\NotNull(
     *     message="Ce champ est obligatoire"
     * )
     * @Assert\GreaterThanOrEqual(100)
     */
    #[Groups([
        'annonce:get',
        'annonce:get:collection'
    ])]
    private $prix;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotNull(
     *     message="Ce champ est obligatoire"
     * )
     * @Assert\GreaterThanOrEqual(100)
     */
    #[Groups([
        'annonce:get',
        'annonce:get:collection'
    ])]
    private $kilometrage;

    /**
     * @ORM\Column(type="string", length=10)
     * @Assert\NotNull(
     *     message="Ce champ est obligatoire"
     * )
     * @Assert\Length(
     *     min = 10,
     *     max= 10,
     *     exactMessage="Ce champt doit faire 10 charactères"
     * )
     */
    #[Groups(['annonce:get'])]
    private $reference;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotNull(
     *     message="Ce champ est obligatoire"
     * )
     * @Assert\LessThan("today")
     */
    #[Groups([
        'annonce:get',
        'annonce:get:collection'
    ])]
    private $anneeCirculation;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     * @Assert\GreaterThanOrEqual(100)
     * @ORM\JoinColumn(nullable=true)
     */
    #[Groups(['annonce:get'])]
    private $prixEffectifVente;

    /**
     * @ORM\ManyToOne(targetEntity=Modele::class, inversedBy="annonces")
     * @ORM\JoinColumn(nullable=false)
     */
    #[Groups([
        'annonce:get',
        'annonce:get:collection'
    ])]
    private $modele;

    /**
     * @ORM\ManyToOne(targetEntity=TypeCarburant::class, inversedBy="annonces")
     * @ORM\JoinColumn(nullable=false)
     */
    #[Groups([
        'annonce:get',
        'annonce:get:collection'
    ])]
    private $typeCarburant;

    /**
     * @ORM\OneToMany(targetEntity=Photo::class, mappedBy="annonce")
     * @Assert\Count(
     *     min=1,
     *     max=10,
     *     minMessage="Il faut renseigner au moins une photo",
     *     maxMessage="Il faut renseigner au maximum 10 photos"
     * )
     */
    #[Groups([
        'annonce:get',
        'annonce:get:collection'
    ])]
    private $photos;

    /**
     * @ORM\ManyToOne(targetEntity=Garage::class, inversedBy="annonces")
     * @ORM\JoinColumn(nullable=false)
     */
    #[Groups(['annonce:get'])]
    private $garage;

    public function __construct()
    {
        $this->photos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPrix(): ?string
    {
        return $this->prix;
    }

    public function setPrix(string $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getKilometrage(): ?int
    {
        return $this->kilometrage;
    }

    public function setKilometrage(int $kilometrage): self
    {
        $this->kilometrage = $kilometrage;

        return $this;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    public function getAnneeCirculation(): ?int
    {
        return $this->anneeCirculation;
    }

    public function setAnneeCirculation(int $anneeCirculation): self
    {
        $this->anneeCirculation = $anneeCirculation;

        return $this;
    }

    public function getPrixEffectifVente(): ?string
    {
        return $this->prixEffectifVente;
    }

    public function setPrixEffectifVente(string $prixEffectifVente): self
    {
        $this->prixEffectifVente = $prixEffectifVente;

        return $this;
    }

    public function getModele(): ?Modele
    {
        return $this->modele;
    }

    public function setModele(?Modele $modele): self
    {
        $this->modele = $modele;

        return $this;
    }

    public function getTypeCarburant(): ?TypeCarburant
    {
        return $this->typeCarburant;
    }

    public function setTypeCarburant(?TypeCarburant $typeCarburant): self
    {
        $this->typeCarburant = $typeCarburant;

        return $this;
    }

    /**
     * @return Collection|Photo[]
     */
    public function getPhotos(): Collection
    {
        return $this->photos;
    }

    public function addPhoto(Photo $photo): self
    {
        if (!$this->photos->contains($photo)) {
            $this->photos[] = $photo;
            $photo->setAnnonce($this);
        }

        return $this;
    }

    public function removePhoto(Photo $photo): self
    {
        if ($this->photos->removeElement($photo)) {
            // set the owning side to null (unless already changed)
            if ($photo->getAnnonce() === $this) {
                $photo->setAnnonce(null);
            }
        }

        return $this;
    }

    public function getGarage(): ?Garage
    {
        return $this->garage;
    }

    public function setGarage(?Garage $garage): self
    {
        $this->garage = $garage;

        return $this;
    }
}
