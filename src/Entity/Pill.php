<?php

namespace App\Entity;

use App\Repository\PillRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=PillRepository::class)
 */
class Pill
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     *  
     * @Groups({"reviews_list", "pills", "reviews_details"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\Length(
     *      max = 100,
     *      maxMessage = "La longueur maximale du nom doit être de {{ limit }} caractères."
     * )     
     * @Groups({"reviews_list", "pills", "user_reviews", "reviews_details"})
     */
    private $name;

    /**
     * @ORM\Column(type="text")
     * @Assert\Length(
     *      max = 1000,
     *      maxMessage = "La longueur maximale de la description doit être de {{ limit }} caractères."
     * )
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=128, options={"default" : "no-pill.jpg"}, nullable=true)
     * 
     */
    private $picture;

    /**
     * @ORM\Column(type="smallint")
     * @Assert\Range(
     *      min = 0,
     *      max = 100,
     *      notInRangeMessage = "Le taux de remboursement doit être compris entre {{ min }} et {{ max }}."
     * )
     */
    private $reimbursed;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Length(
     *      max = 255,
     *      maxMessage = "La longueur maximale doit être de {{ limit }} caractères."
     * ) 
     */
    private $generic;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\Length(
     *      max = 100,
     *      maxMessage = "La longueur maximale doit être de {{ limit }} caractères."
     * ) 
     */
    private $posology;

    /**
     * @ORM\Column(type="string", length=64, nullable=true)
     * @Assert\Length(
     *      max = 64,
     *      maxMessage = "La longueur maximale doit être de {{ limit }} caractères."
     * ) 
     */
    private $type;

    /**
     * @ORM\Column(type="smallint")
     * @Assert\NotBlank
     */
    private $generation;

    /**
     * @ORM\Column(type="boolean")
     * @Assert\Type("boolean")
     */
    private $interruption;

    /**
     * @ORM\Column(type="string", length=64)
     * @Assert\Length(
     *      max = 64,
     *      maxMessage = "La longueur maximale doit être de {{ limit }} caractères."
     * )
     */
    private $laboratory;

    /**
     * @ORM\Column(type="smallint")
     * @Assert\NotBlank
     * 
     */
    private $delay_intake;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Assert\Length(
     *      max = 1000,
     *      maxMessage = "La longueur maximale doit être de {{ limit }} caractères."
     * )
     * 
     */
    private $composition;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank
     */
    private $count_reviews;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     * @Assert\Length(
     *      max = 100,
     *      maxMessage = "La longueur maximale doit être de {{ limit }} caractères."
     * )
     */
    private $slug;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $updated_at;

    /**
     * @ORM\OneToMany(targetEntity=ReviewPill::class, mappedBy="pill", orphanRemoval=true)
     */
    private $reviews;

    public function __construct()
    {
        $this->reviews = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(string $picture): self
    {
        $this->picture = $picture;

        return $this;
    }

    public function getReimbursed(): ?int
    {
        return $this->reimbursed;
    }

    public function setReimbursed(int $reimbursed): self
    {
        $this->reimbursed = $reimbursed;

        return $this;
    }

    public function getGeneric(): ?string
    {
        return $this->generic;
    }

    public function setGeneric(?string $generic): self
    {
        $this->generic = $generic;

        return $this;
    }

    public function getPosology(): ?string
    {
        return $this->posology;
    }

    public function setPosology(string $posology): self
    {
        $this->posology = $posology;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getGeneration(): ?int
    {
        return $this->generation;
    }

    public function setGeneration(int $generation): self
    {
        $this->generation = $generation;

        return $this;
    }

    public function getInterruption(): ?bool
    {
        return $this->interruption;
    }

    public function setInterruption(bool $interruption): self
    {
        $this->interruption = $interruption;

        return $this;
    }

    public function getLaboratory(): ?string
    {
        return $this->laboratory;
    }

    public function setLaboratory(string $laboratory): self
    {
        $this->laboratory = $laboratory;

        return $this;
    }

    public function getDelayIntake(): ?int
    {
        return $this->delay_intake;
    }

    public function setDelayIntake(int $delay_intake): self
    {
        $this->delay_intake = $delay_intake;

        return $this;
    }

    public function getComposition(): ?string
    {
        return $this->composition;
    }

    public function setComposition(?string $composition): self
    {
        $this->composition = $composition;

        return $this;
    }

    public function getCountReviews(): ?int
    {
        return $this->count_reviews;
    }

    public function setCountReviews(int $count_reviews): self
    {
        $this->count_reviews = $count_reviews;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): self
    {
        $this->slug = $slug;

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
    public function getReviews(): Collection
    {
        return $this->reviews;
    }

    public function addReview(ReviewPill $review): self
    {
        if (!$this->reviews->contains($review)) {
            $this->reviews[] = $review;
            $review->setPill($this);
        }

        return $this;
    }

    public function removeReview(ReviewPill $review): self
    {
        if ($this->reviews->removeElement($review)) {
            // set the owning side to null (unless already changed)
            if ($review->getPill() === $this) {
                $review->setPill(null);
            }
        }

        return $this;
    }
}
