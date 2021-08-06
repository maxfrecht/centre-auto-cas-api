<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\GarageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=GarageRepository::class)
 */
#[ApiResource(
    collectionOperations: [
        'get' => ['normalization_context' => ['groups' => 'garage:get:collection']],
        'post',
    ],
    itemOperations: ['get', 'put', 'delete', 'patch'],
    attributes: ['security' => "is_granted('ROLE_USER')"],
    normalizationContext: ['groups' => 'garage:get']
)]
class Garage
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    #[Groups([
        'garage:get',
        'garage:get:collection',
        'adresse:get',
        'user:get'
    ])]
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotNull(
     *     message="Ce champ est obligatoire"
     * )
     * @Assert\Length(
     *     min = 2,
     *     max= 255,
     *     minMessage = "Ce champ doit faire 2 charactères minimum",
     *     maxMessage="Ce champ ne peut pas exceder 255 charactères"
     * )
     */
    #[Groups([
        'garage:get',
        'garage:get:collection',
        'adresse:get',
        'user:get'
    ])]
    private $nom;

    /**
     * @ORM\Column(type="string", length=15)
     * @Assert\NotNull(
     *     message="Ce champ est obligatoire"
     * )
     * @Assert\Length(
     *     min = 15,
     *     max= 15,
     *     exactMessage="Un numéro de téléphone doit faire 15 charactères"
     * )
     */
    #[Groups([
        'garage:get',
        'garage:get:collection'
    ])]
    private $numeroTel;

    /**
     * @ORM\OneToMany(targetEntity=Annonce::class, mappedBy="garage")
     */
    #[Groups([
        'garage:get'
    ])]
    private $annonces;

    /**
     * @ORM\ManyToOne(targetEntity=Adresse::class, inversedBy="garages")
     * @ORM\JoinColumn(nullable=false)
     */
    #[Groups([
        'garage:get'
    ])]
    private $adresse;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="garages")
     * @ORM\JoinColumn(nullable=false)
     */
    #[Groups([
        'garage:get',
        'garage:get:collection'
    ])]
    private $user;

    public function __construct()
    {
        $this->annonces = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getNumeroTel(): ?string
    {
        return $this->numeroTel;
    }

    public function setNumeroTel(string $numeroTel): self
    {
        $this->numeroTel = $numeroTel;

        return $this;
    }

    /**
     * @return Collection|Annonce[]
     */
    public function getAnnonces(): Collection
    {
        return $this->annonces;
    }

    public function addAnnonce(Annonce $annonce): self
    {
        if (!$this->annonces->contains($annonce)) {
            $this->annonces[] = $annonce;
            $annonce->setGarage($this);
        }

        return $this;
    }

    public function removeAnnonce(Annonce $annonce): self
    {
        if ($this->annonces->removeElement($annonce)) {
            // set the owning side to null (unless already changed)
            if ($annonce->getGarage() === $this) {
                $annonce->setGarage(null);
            }
        }

        return $this;
    }

    public function getAdresse(): ?Adresse
    {
        return $this->adresse;
    }

    public function setAdresse(?Adresse $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
