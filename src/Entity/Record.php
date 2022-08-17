<?php

namespace App\Entity;

use App\Repository\RecordRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RecordRepository::class)]
class Record
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column()]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $text = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    private array $images = [];

    #[ORM\ManyToOne(inversedBy: 'records')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Colors $color = null;

    #[ORM\ManyToOne(inversedBy: 'records')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Geometry $geometry = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
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

    public function getImages(): array
    {
        return $this->images;
    }

    public function setImages($images): self
    {
        $this->images[] = $images;

        return $this;
    }

    public function getColor(): ?Colors
    {
        return $this->color;
    }

    public function setColor(?Colors $color): self
    {
        $this->color = $color;

        return $this;
    }

    public function getGeometry(): ?Geometry
    {
        return $this->geometry;
    }

    public function setGeometry(?Geometry $geometry): self
    {
        $this->geometry = $geometry;

        return $this;
    }
}
