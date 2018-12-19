<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\WorkRepository")
 */
class Work
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Author", mappedBy="works")
     */
    private $authors;

    /**
     * @ORM\Column(type="json")
     */
    private $citation = [];

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $year;

    public function __construct(Citation $citation = null)
    {
        $this->authors = new ArrayCollection();
        
        if ($citation != null) {
            $this->setTitle($citation->title);
            $this->setType($citation->type);
            $this->setYear($citation->year);
            $this->setCitation($citation);
        }
    }
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection|Author[]
     */
    public function getAuthors(): Collection
    {
        return $this->authors;
    }

    public function addAuthor(Author $author): self
    {
        if (!$this->authors->contains($author)) {
            $this->authors[] = $author;
            $author->addWork($this);
        }

        return $this;
    }

    public function removeAuthor(Author $author): self
    {
        if ($this->authors->contains($author)) {
            $this->authors->removeElement($author);
            $author->removeWork($this);
        }

        return $this;
    }

    public function getCitation(): ?Citation
    {
        $citation = new Citation();
        $citation->fromArray($this->citation);
        
        return $citation;
    }

    public function setCitation(Citation $citation): self
    {
        $this->citation = $citation->toArray();

        return $this;
    }

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function setYear(?int $year): self
    {
        $this->year = $year;

        return $this;
    }
}
