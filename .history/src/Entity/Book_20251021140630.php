<?php

namespace App\Entity;

use App\Repository\BookRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BookRepository::class)]
class Book
{
    public function __construct()
    {
        $this->readers = new \Doctrine\Common\Collections\ArrayCollection();
    }
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $publicationDate = null;

    #[ORM\Column]
    private ?bool $published = null;

    #[ORM\ManyToOne(targetEntity: Author::class, inversedBy: "books")]
    #[ORM\JoinColumn(nullable: false)]
    private ?Author $author = null;

    public function getId(): ?int
    {
        return $this->id;
    }
    public function getAuthor(): ?Author
    {
        return $this->author;
    }

    public function setAuthor(?Author $author): static
    {
        $this->author = $author;
        return $this;
    }

    #[ORM\ManyToMany(targetEntity: Reader::class, inversedBy: "books")]
    private $readers;

    public function getReaders()
    {
        return $this->readers;
    }

    public function addReader(Reader $reader): static
    {
        if (!$this->readers->contains($reader)) {
            $this->readers[] = $reader;
        }
        return $this;
    }

    public function removeReader(Reader $reader): static
    {
        $this->readers->removeElement($reader);
        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getPublicationDate(): ?\DateTime
    {
        return $this->publicationDate;
    }

    public function setPublicationDate(\DateTime $publicationDate): static
    {
        $this->publicationDate = $publicationDate;

        return $this;
    }

    public function isPublished(): ?bool
    {
        return $this->published;
    }

    public function setPublished(bool $published): static
    {
        $this->published = $published;
        return $this;
    }
}
