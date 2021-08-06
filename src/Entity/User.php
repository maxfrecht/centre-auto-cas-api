<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
#[ApiResource(
    collectionOperations: [
        'get' => [
            'normalization_context' => ['groups' => ['user:get:collection']],
            'security' => "is_granted('ROLE_ADMIN')"
            ],
        'post' => ['security' => "is_granted('ROLE_ADMIN')"]
    ],
    itemOperations: [
        'get',
        'put' => ['security' => "is_granted('ROLE_ADMIN')"],
        'delete' => ['security' => "is_granted('ROLE_ADMIN')"],
        'patch' => ['security' => "is_granted('ROLE_ADMIN')"],
    ],
    attributes: ['security' => "is_granted('ROLE_USER')"],
    normalizationContext: ['groups' => ['user:get']]
)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    #[Groups([
        'garage:get',
        'garage:get:collection',
        'user:get',
        'user:get:collection',
    ])]
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\Email(
     *     message= "L'email '{{ value }}' n'est pas valide"
     * )
     */
    #[Groups([
        'garage:get',
        'garage:get:collection',
        'user:get',
        'user:get:collection'
    ])]
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    #[Groups([
        'user:get'
    ])]
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    #[Groups([
        'user:get'
    ])]
    private $password;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
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
        'user:get',
        'user:get:collection'
    ])]
    private $nom;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
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
        'user:get',
        'user:get:collection'
    ])]
    private $prenom;

    /**
     * @ORM\Column(type="string", length=15, nullable=true)
     * @Assert\Length(
     *     min = 15,
     *     max= 15,
     *     exactMessage="Un numéro de téléphone doit faire 15 charactères"
     * )
     */
    #[Groups([
        'garage:get',
        'user:get'
    ])]
    private $numTel;

    /**
     * @ORM\Column(type="string", length=14, nullable=true)
     * @Assert\Length(
     *     min = 14,
     *     max= 14,
     *     exactMessage="Un numéro de SIRET doit faire 14 charactères"
     * )
     */
    #[Groups([
        'user:get'
    ])]
    private $siret;

    /**
     * @ORM\OneToMany(targetEntity=Garage::class, mappedBy="user", orphanRemoval=true)
     */
    #[Groups([
        'user:get'
    ])]
    private $garages;

    public function __construct()
    {
        $this->garages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(?string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getNumTel(): ?string
    {
        return $this->numTel;
    }

    public function setNumTel(?string $numTel): self
    {
        $this->numTel = $numTel;

        return $this;
    }

    public function getSiret(): ?string
    {
        return $this->siret;
    }

    public function setSiret(?string $siret): self
    {
        $this->siret = $siret;

        return $this;
    }

    /**
     * @return Collection|Garage[]
     */
    public function getGarages(): Collection
    {
        return $this->garages;
    }

    public function addGarage(Garage $garage): self
    {
        if (!$this->garages->contains($garage)) {
            $this->garages[] = $garage;
            $garage->setUser($this);
        }

        return $this;
    }

    public function removeGarage(Garage $garage): self
    {
        if ($this->garages->removeElement($garage)) {
            // set the owning side to null (unless already changed)
            if ($garage->getUser() === $this) {
                $garage->setUser(null);
            }
        }

        return $this;
    }
}
