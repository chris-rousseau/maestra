<?php

namespace App\Entity;

use App\Repository\ReviewPillRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ReviewPillRepository::class)
 */
class ReviewPill
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * 
     * @Groups({"pill_reviews", "reviews_details"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=64)
     * 
     * @Assert\Length(
     *      min = 5,
     *      max = 64,
     *      minMessage = "Merci de saisir un titre d'au moins {{ limit }} caractères.",
     *      maxMessage = "La longueur maximale d'un titre doit être de {{ limit }} caractères."
     * )
     * @Groups({"reviews_list", "reviews_details", "user_reviews", "pill_reviews"})
     */
    private $title;

    /**
     * @ORM\Column(type="integer", options={"default" : 0}, nullable=true)
     * @Groups({"pill_reviews", "user_reviews", "reviews_details"})
     */
    private $rate;

    /**
     * @ORM\Column(type="text")
     * @Assert\Length(
     *      min = 25,
     *      max = 1500,
     *      minMessage = "Merci de saisir un avis d'au moins {{ limit }} caractères.",
     *      maxMessage = "La longueur maximale d'un avis doit être de {{ limit }} caractères."
     * )
     * @Groups({"reviews_list", "pill_reviews", "user_reviews", "reviews_details"})
     */
    private $content;

    /**
     * @ORM\Column(type="smallint")
     * @Assert\Range(
     *      min = -1,
     *      max = 1,
     *      notInRangeMessage = "Une erreur est survenue"
     * )
     */
    private $acne;

    /**
     * @ORM\Column(type="smallint")
     * @Assert\Range(
     *      min = -1,
     *      max = 1,
     *      notInRangeMessage = "Une erreur est survenue"
     * )
     */
    private $libido;

    /**
     * @ORM\Column(type="smallint")
     * @Assert\Range(
     *      min = -1,
     *      max = 1,
     *      notInRangeMessage = "Une erreur est survenue"
     * )
     */
    private $migraine;

    /**
     * @ORM\Column(type="smallint")
     * @Assert\Range(
     *      min = -1,
     *      max = 1,
     *      notInRangeMessage = "Une erreur est survenue"
     * )
     */
    private $weight;

    /**
     * @ORM\Column(type="smallint")
     * @Assert\Range(
     *      min = -1,
     *      max = 1,
     *      notInRangeMessage = "Une erreur est survenue"
     * )
     */
    private $breast_pain;

    /**
     * @ORM\Column(type="smallint")
     * @Assert\Range(
     *      min = -1,
     *      max = 1,
     *      notInRangeMessage = "Une erreur est survenue"
     * )
     */
    private $nausea;

    /**
     * @ORM\Column(type="smallint")
     * @Assert\Range(
     *      min = -1,
     *      max = 1,
     *      notInRangeMessage = "Une erreur est survenue"
     * )
     */
    private $pms;

    /**
     * @ORM\Column(type="boolean")
     * @Assert\Type("boolean")
     */
    private $perturbation_period;

    /**
     * @ORM\Column(type="smallint")
     */
    private $status;

    /**
     * @ORM\Column(type="datetime_immutable")
     * @Groups({"pill_reviews", "reviews_details"})
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $updated_at;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="reviewPills")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"reviews_list", "reviews_details", "pill_reviews"})
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Pill::class, inversedBy="reviews")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"reviews_list", "reviews_details", "user_reviews"})
     */
    private $pill;

    public function __construct()
    {
        $this->created_at = new DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getRate(): ?int
    {
        return $this->rate;
    }

    public function setRate(int $rate): self
    {
        $this->rate = $rate;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getAcne(): ?int
    {
        return $this->acne;
    }

    public function setAcne(int $acne): self
    {
        $this->acne = $acne;

        return $this;
    }

    public function getLibido(): ?int
    {
        return $this->libido;
    }

    public function setLibido(int $libido): self
    {
        $this->libido = $libido;

        return $this;
    }

    public function getMigraine(): ?int
    {
        return $this->migraine;
    }

    public function setMigraine(int $migraine): self
    {
        $this->migraine = $migraine;

        return $this;
    }

    public function getWeight(): ?int
    {
        return $this->weight;
    }

    public function setWeight(int $weight): self
    {
        $this->weight = $weight;

        return $this;
    }

    public function getBreastPain(): ?int
    {
        return $this->breast_pain;
    }

    public function setBreastPain(int $breast_pain): self
    {
        $this->breast_pain = $breast_pain;

        return $this;
    }

    public function getNausea(): ?int
    {
        return $this->nausea;
    }

    public function setNausea(int $nausea): self
    {
        $this->nausea = $nausea;

        return $this;
    }

    public function getPms(): ?int
    {
        return $this->pms;
    }

    public function setPms(int $pms): self
    {
        $this->pms = $pms;

        return $this;
    }

    public function getPerturbationPeriod(): ?bool
    {
        return $this->perturbation_period;
    }

    public function setPerturbationPeriod(bool $perturbation_period): self
    {
        $this->perturbation_period = $perturbation_period;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getPill(): ?Pill
    {
        return $this->pill;
    }

    public function setPill(?Pill $pill): self
    {
        $this->pill = $pill;

        return $this;
    }
}
