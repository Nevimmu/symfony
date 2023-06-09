<?php

namespace App\Entity;

use App\Repository\BookRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BookRepository::class)]
class Book
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Titre = null;

    #[ORM\Column]
    private ?float $Prix = null;

    #[ORM\Column]
    private ?bool $Lu = False;

    #[ORM\Column]
    private ?bool $Reading = False;

    #[ORM\Column]
    private ?int $total_time = 0;

    #[ORM\Column]
    private ?int $start_time = 0;

    #[ORM\ManyToOne(inversedBy: 'books')]
    private ?Author $author = null;

    public function __construct()
    {
        $this->author = new Author();
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->Titre;
    }

    public function setTitre(string $Titre): self
    {
        $this->Titre = $Titre;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->Prix;
    }

    public function setPrix(float $Prix): self
    {
        $this->Prix = $Prix;

        return $this;
    }

    public function isLu(): ?bool
    {
        return $this->Lu;
    }

    public function setLu(bool $Lu): self
    {
        $this->Lu = $Lu;

        return $this;
    }

    public function isReading(): ?bool
    {
        return $this->Reading;
    }

    public function setReading(bool $Reading): self
    {
        $this->Reading = $Reading;

        return $this;
    }

    public function getTotalTime(): ?int
    {
        return $this->total_time;
    }

    public function setTotalTime(int $total_time): self
    {
        $this->total_time = $total_time;

        return $this;
    }

    public function getStartTime(): ?int
    {
        return $this->start_time;
    }

    public function setStartTime(int $start_time): self
    {
        $this->start_time = $start_time;

        return $this;
    }

    public function getAuthor(): ?Author
    {
        return $this->author;
    }

    public function setAuthor(?Author $author): self
    {
        $this->author = $author;

        return $this;
    }
}
