<?php

namespace App\Entity;

use App\Repository\ReviewsPillsRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Bundle\FixturesBundle;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Entity(repositoryClass=ReviewsPillsRepository::class)
 */
class ReviewsPills
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * 
     * @Groups({"reviews", "pill_reviews"})
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     * 
     * @Groups({"reviews", "pill_reviews", "user_reviews"})
     */
    private $rate;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank
     * @Groups({"reviews", "pill_reviews", "user_reviews"})
     */
    private $content;

    /**
     * @ORM\Column(type="smallint")
     * 
     * @Groups({"reviews", "user_reviews"})
     */
    private $acne;

    /**
     * @ORM\Column(type="smallint")
     * 
     * @Groups({"reviews", "user_reviews"})
     */
    private $libido;

    /**
     * @ORM\Column(type="smallint")
     * 
     * @Groups({"reviews", "user_reviews"})
     */
    private $migraine;

    /**
     * @ORM\Column(type="smallint")
     * 
     * @Groups({"reviews", "user_reviews"})
     */
    private $weight;

    /**
     * @ORM\Column(type="smallint")
     * 
     * @Groups({"reviews", "user_reviews"})
     */
    private $breast_pain;

    /**
     * @ORM\Column(type="smallint")
     * 
     * @Groups({"reviews", "user_reviews"})
     */
    private $nausea;

    /**
     * @ORM\Column(type="smallint")
     * 
     * @Groups({"reviews", "user_reviews"})
     */
    private $pms;

    /**
     * @ORM\Column(type="string", length=3)
     * 
     *  @Groups({"reviews", "pill_reviews", "user_reviews"})
     */
    private $perturbation_period;

    /**
     * @ORM\Column(type="datetime_immutable")
     * 
     * @Groups({"reviews", "pill_reviews"})
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     * 
     */
    private $updated_at;

    /**
     * @ORM\ManyToOne(targetEntity=Pills::class, inversedBy="reviews")
     * @ORM\JoinColumn(nullable=false)
     * 
     * @Groups({"reviews", "user_reviews"})
     */
    private $pill;

    /**
     * @ORM\ManyToOne(targetEntity=Users::class, inversedBy="reviews")
     * @ORM\JoinColumn(nullable=false)
     * 
     * @Groups({"reviews", "pill_reviews"})
     *
     */
    private $user;

    public function __construct()
    {
        $this->created_at = new DateTimeImmutable();
        $this->updated_at = new DateTimeImmutable();
    }
    /**
     * @ORM\Column(type="smallint")
     */
    private $status;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $title;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getPerturbationPeriod(): ?string
    {
        return $this->perturbation_period;
    }

    public function setPerturbationPeriod(string $perturbation_period): self
    {
        $this->perturbation_period = $perturbation_period;

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

    public function getPill(): ?Pills
    {
        return $this->pill;
    }

    public function setPill(?Pills $pill): self
    {
        $this->pill = $pill;

        return $this;
    }

    public function getUser(): ?Users
    {
        return $this->user;
    }

    public function setUser(?Users $user): self
    {
        $this->user = $user;

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

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }
}
