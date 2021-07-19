<?php

namespace App\Entity;

use App\Repository\PillsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PillsRepository::class)
 */
class Pills
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $name;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=128)
     */
    private $picture;

    /**
     * @ORM\Column(type="smallint")
     */
    private $reimbursed;

    /**
     * @ORM\Column(type="smallint")
     */
    private $generation;

    /**
     * @ORM\Column(type="string", length=64, nullable=true)
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=3)
     */
    private $interruption;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $laboratory;

    /**
     * @ORM\Column(type="smallint")
     */
    private $delay_intake;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $composition;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
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

    public function getGeneration(): ?int
    {
        return $this->generation;
    }

    public function setGeneration(int $generation): self
    {
        $this->generation = $generation;

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

    public function getInterruption(): ?string
    {
        return $this->interruption;
    }

    public function setInterruption(string $interruption): self
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

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

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
}
