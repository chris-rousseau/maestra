<?php

namespace App\Entity;

use App\Repository\UserRepository;
use DateTimeImmutable;
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
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * 
     * @Groups({"reviews_list", "users", "reviews_details", "pill_reviews"})
     * 
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\NotBlank(message="Merci de saisir votre adresse email")
     * @Assert\Email(
     *     message = "L'email saisie n'est pas valide"
     * )
     * @Groups({"users"})
     */
    private $email;

    /**
     * @ORM\Column(type="json", nullable=true, options={"default" : "[]"})
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @Assert\NotBlank(message="Le mot de passe ne peut pas être vide")
     * @Assert\Regex(
     * pattern = "#^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).{8,}$#",
     * match=true,
     * message="Votre mot de passe doit comporter au moins huit caractères, dont au moins une majuscule et minuscule, un chiffre et un symbole."
     * )
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=64)
     * @Assert\Length(
     *      min = 2,
     *      max = 64,
     *      minMessage = "Le prénom doit avoir au minimum {{ limit }} caractères.",
     *      maxMessage = "Le prénom doit avoir au maximum {{ limit }} caractères."
     * )
     * @Groups({"users", "reviews_details", "reviews_list", "pill_reviews"})
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=64)
     * @Assert\Length(
     *      min = 2,
     *      max = 64,
     *      minMessage = "Le nom doit avoir au minimum {{ limit }} caractères.",
     *      maxMessage = "Le nom doit avoir au maximum {{ limit }} caractères."
     * )
     * @Groups({"users"})
     */
    private $lastname;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank(message="Merci de saisir votre date de naissance")
     * @Assert\Date
     * @var string A "Y-m-d" formatted value
     * @Groups({"users", "reviews_details", "reviews_list"})
     */
    private $birthdate;

    /**
     * @ORM\Column(type="string", length=128, nullable=true, options={"default" : "no-avatar.jpg"})
     * @Groups({"users", "reviews_list", "reviews_details", "pill_reviews"})
     */
    private $picture;

    /**
     * @ORM\Column(type="boolean")
     * @Assert\Type("boolean")
     * @Groups({"users", "reviews_list", "reviews_details", "pill_reviews"})
     */
    private $smoker;

    /**
     * @ORM\Column(type="boolean")
     * @Assert\Type("boolean")
     * @Groups({"users", "reviews_list", "reviews_details", "pill_reviews"})
     */
    private $children;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $updated_at;

    /**
     * @ORM\OneToMany(targetEntity=ReviewPill::class, mappedBy="user", orphanRemoval=true)
     */
    private $reviewPills;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $token;

    public function __construct()
    {
        $this->reviewPills = new ArrayCollection();
        $this->created_at = new DateTimeImmutable();
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

    public function getBirthdate(): ?string
    {
        return $this->birthdate;
    }

    public function setBirthdate(string $birthdate): self
    {
        $this->birthdate = $birthdate;

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(?string $picture): self
    {
        $this->picture = $picture;

        return $this;
    }

    public function getSmoker(): ?bool
    {
        return $this->smoker;
    }

    public function setSmoker(bool $smoker): self
    {
        $this->smoker = $smoker;

        return $this;
    }

    public function getChildren(): ?bool
    {
        return $this->children;
    }

    public function setChildren(bool $children): self
    {
        $this->children = $children;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    /**
     * @return Collection|ReviewPill[]
     */
    public function getReviewPills(): Collection
    {
        return $this->reviewPills;
    }

    public function addReviewPill(ReviewPill $reviewPill): self
    {
        if (!$this->reviewPills->contains($reviewPill)) {
            $this->reviewPills[] = $reviewPill;
            $reviewPill->setUser($this);
        }

        return $this;
    }

    public function removeReviewPill(ReviewPill $reviewPill): self
    {
        if ($this->reviewPills->removeElement($reviewPill)) {
            // set the owning side to null (unless already changed)
            if ($reviewPill->getUser() === $this) {
                $reviewPill->setUser(null);
            }
        }

        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(?string $token): self
    {
        $this->token = $token;

        return $this;
    }
}
