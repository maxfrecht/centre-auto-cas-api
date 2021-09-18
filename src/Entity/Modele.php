<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Repository\ModeleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ModeleRepository::class)
 */
#[ApiResource(
    collectionOperations: [
        'get' => ['normalization_context' => ['groups' => ['modele:get:collection']]],
        'post' => ['security' => "is_granted('ROLE_ADMIN')"],
    ],
    itemOperations: [
        'get',
        'put' => ['security' => "is_granted('ROLE_ADMIN')"],
        'delete' => ['security' => "is_granted('ROLE_ADMIN')"],
        'patch' => ['security' => "is_granted('ROLE_ADMIN')"],
    ],
    normalizationContext: ['groups' => ['modele:get']]
)]
#[ApiFilter(SearchFilter::class, properties:['marque.nom' => 'exact'])]
class Modele
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    #[Groups([
        'annonce:get',
        'annonce:get:collection',
        'marque:get',
        'modele:get',
        'modele:get:collection'
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
        'annonce:get',
        'annonce:get:collection',
        'marque:get',
        'modele:get',
        'modele:get:collection'
    ])]
    private $nom;

    /**
     * @ORM\OneToMany(targetEntity=Annonce::class, mappedBy="modele")
     */
    #[Groups(['modele:get'])]
    private $annonces;

    /**
     * @ORM\ManyToOne(targetEntity=Marque::class, inversedBy="modeles")
     * @ORM\JoinColumn(nullable=false)
     */
    #[Groups([
        'annonce:get',
        'annonce:get:collection',
        'modele:get',
        'modele:get:collection'
    ])]
    private $marque;

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
            $annonce->setModele($this);
        }

        return $this;
    }

    public function removeAnnonce(Annonce $annonce): self
    {
        if ($this->annonces->removeElement($annonce)) {
            // set the owning side to null (unless already changed)
            if ($annonce->getModele() === $this) {
                $annonce->setModele(null);
            }
        }

        return $this;
    }

    public function getMarque(): ?Marque
    {
        return $this->marque;
    }

    public function setMarque(?Marque $marque): self
    {
        $this->marque = $marque;

        return $this;
    }
}
