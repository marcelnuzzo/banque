<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 *  @UniqueEntity(
 *  fields={"email"},
 *  message="Un autre utilisateur s'est déjà inscrit avec cette adresse email, merci de la modifier"
 * )
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\Email(message="Veuillez renseigner un email valide")
     * @Assert\NotBlank()
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

     /**
     *  @Assert\EqualTo(propertyPath="password", message="Vous n'avez pas correctement confirmé votre mot de passe") 
     */
    public $passwordConfirm;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $lastname;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank()
     */
    private $birthdayAt;

    /**
     * @ORM\OneToMany(targetEntity=Bankaccount::class, mappedBy="users", orphanRemoval=true)
     */
    private $bankaccounts;

    public function __construct()
    {
        $this->bankaccounts = new ArrayCollection();
    }

    public function getFullName() {
        return "{$this->firstname} {$this->lastname}";
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
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getBirthdayAt(): ?\DateTimeInterface
    {
        return $this->birthdayAt;
    }

    public function setBirthdayAt(\DateTimeInterface $birthdayAt): self
    {
        $this->birthdayAt = $birthdayAt;

        return $this;
    }

    /**
     * @return Collection|Bankaccount[]
     */
    public function getBankaccounts(): Collection
    {
        return $this->bankaccounts;
    }

    public function addBankaccount(Bankaccount $bankaccount): self
    {
        if (!$this->bankaccounts->contains($bankaccount)) {
            $this->bankaccounts[] = $bankaccount;
            $bankaccount->setUsers($this);
        }

        return $this;
    }

    public function removeBankaccount(Bankaccount $bankaccount): self
    {
        if ($this->bankaccounts->removeElement($bankaccount)) {
            // set the owning side to null (unless already changed)
            if ($bankaccount->getUsers() === $this) {
                $bankaccount->setUsers(null);
            }
        }

        return $this;
    }
}
