<?php

namespace App\Entity;

use App\Repository\ReaderRepository;
use Doctrine\ORM\Mapping as ORM;

use App\Entity\Book;

#[ORM\Entity(repositoryClass: ReaderRepository::class)]
class Reader
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $username = null;

    #[ORM\ManyToMany(targetEntity: Book::class, mappedBy: "readers")]
    private $books;

    public function __construct()
    {
        $this->books = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }
    public function getBooks()
    {
        return $this->books;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }
}
