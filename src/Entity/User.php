<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    protected ?int $id;

    #[ORM\Column(type: 'string', length: 60, unique: true)]
    #[Assert\NotBlank(message: "Vous devez saisir un nom d'utilisateur.")]
    #[Assert\Length(max: 60, maxMessage: "le nom d'utilisateur ne doit pas dépasser {{ limit }} caractères")]
    private ?string $username;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(message: 'Vous devez saisir un mot de passe.')]
    #[Assert\Length(max: 255, maxMessage: 'le mot de passe ne doit pas dépasser {{ limit }} caractères')]
    private ?string $password;

    #[ORM\Column(type: 'string', length: 100)]
    #[Assert\NotBlank(message: 'Vous devez saisir une adresse email.')]
    #[Assert\Email(message: "Le format de l'adresse n'est pas correcte.")]
    #[Assert\Length(max: 60, maxMessage: "L'adresse email ne doit pas dépasser {{ limit }} caractères")]
    private ?string $email;

    #[ORM\ManyToMany(targetEntity: Role::class)]
    private $roles;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Task::class, orphanRemoval: true)]
    private $tasks;

    public function __construct()
    {
        $this->roles = new ArrayCollection();
        $this->tasks = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles->map(function (Role $role) {
            return $role->getRoleName();
        });

        return array_unique($roles->toArray());
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
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

    public function addRole(Role $role): self
    {
        if (!$this->roles->contains($role)) {
            $this->roles[] = $role;
        }

        return $this;
    }

    /**
     * @return Collection<int, Task>
     */
    public function getTasks(): Collection
    {
        return $this->tasks;
    }

    public static function fromFixture(): self
    {
        return (new User())
            ->setUsername(uniqid())
            ->setPassword(uniqid())
            ->setEmail(uniqid().'@todolist.com');
    }
}
