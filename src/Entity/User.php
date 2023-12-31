<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private $role = 'user';

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $username = null;
    #[ORM\ManyToMany(targetEntity: Project::class, mappedBy: 'users')]
    private Collection $projects;
    #[ORM\OneToMany(targetEntity: Publication::class, mappedBy: 'author')]
    private Collection $publications;
        

    #[ORM\OneToMany(targetEntity: Equipment::class, mappedBy: 'creator')]
private Collection $equipments;

#[ORM\Column(type: "string", length: 255, nullable: true)]
private ?string $chercheur = null;

    public function __construct()
    {
        $this->publications = new ArrayCollection();
        $this->projects = new ArrayCollection();
        $this->equipments = new ArrayCollection();


    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
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
     * @see UserInterface
     */
    public function getRoles(): array
    {
        return [$this->role];

    }

    public function setRoles(string $role): self
    {
        $this->role = $role;
    
        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(?string $username): static
    {
        $this->username = $username;

        return $this;
    }
  
    /**
     * @return Collection|Publication[]
     */
    public function getPublications(): Collection
    {
        if ($this->publications === null) {
            $this->publications = new ArrayCollection();
        }
        return $this->publications;
    }

public function getProjects(): Collection
{
    return $this->projects;
}

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): static
    {
        $this->role = $role;

        return $this;
    }

    /**
 * @return Collection<int, Equipment>
 */
public function getEquipments(): Collection
{
    return $this->equipments;
}

public function getChercheur(): ?string
{
    return $this->chercheur;
}

public function setChercheur(?string $chercheur): self
{
    $this->chercheur = $chercheur;
    return $this;
}
public function addProject(Project $project): self
{
    if (!$this->projects->contains($project)) {
        $this->projects[] = $project;
    }

    return $this;
}

public function removeProject(Project $project): self
{
    $this->projects->removeElement($project);

    return $this;
}
}
