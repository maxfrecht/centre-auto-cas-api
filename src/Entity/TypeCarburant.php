<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\TypeCarburantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=TypeCarburantRepository::class)
 */
#[ApiResource(
    collectionOperations: [
        'get' => ['normalization_context' => ['groups' => ['carburant:get:collection']]],
        'post' => ['security' => "is_granted('ROLE_ADMIN')"]
    ],
    itemOperations: [
        'get',
        'put' => ['security' => "is_granted('ROLE_ADMIN')"],
        'delete' => ['security' => "is_granted('ROLE_ADMIN')"],
        'patch' => ['security' => "is_granted('ROLE_ADMIN')"],
    ],
    attributes: ['security' => "is_granted('ROLE_USER')"],
    normalizationContext: ['groups' => ['carburant:get']]
)]
class TypeCarburant
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    #[Groups([
        'annonce:get',
        'annonce:get:collection',
        'carburant:get',
        'carburant:get:collection'
    ])]
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    #[Groups([
        'annonce:get',
        'annonce:get:collection',
        'carburant:get',
        'carburant:get:collection'
    ])]
    private $libelle;

    /**
     * @ORM\OneToMany(targetEntity=Annonce::class, mappedBy="typeCarburant")
     */
    #[Groups(['carburant:get'])]
    private $annonces;

    public function __construct()
    {
        $this->annonces = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

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
            $annonce->setTypeCarburant($this);
        }

        return $this;
    }

    public function removeAnnonce(Annonce $annonce): self
    {
        if ($this->annonces->removeElement($annonce)) {
            // set the owning side to null (unless already changed)
            if ($annonce->getTypeCarburant() === $this) {
                $annonce->setTypeCarburant(null);
            }
        }

        return $this;
    }
}
