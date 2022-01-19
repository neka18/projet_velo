<?php

namespace App\Entity;

use App\Repository\CommentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass=CommentRepository::class)
 */
class Comment
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     * @Gedmo\Blameable(on="create")
     */
    private $author;

    /**
     * @ORM\ManyToOne(targetEntity=Advert::class, inversedBy="comments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $advert;

    /**
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable(on="create")
     */
    private $date;

    /**
     * @ORM\OneToMany(targetEntity=CommentFlag::class, mappedBy="comment", orphanRemoval=true, cascade={"ALL"})
     */
    private $flags;

    /**
     * @ORM\Column(type="boolean")
     */
    private $disabled;

    public function __construct()
    {
        $this->flags = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
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

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getAdvert(): ?Advert
    {
        return $this->advert;
    }

    public function setAdvert(?Advert $advert): self
    {
        $this->advert = $advert;

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

    /**
     * @return Collection|CommentFlag[]
     */
    public function getFlags(): Collection
    {
        return $this->flags;
    }

    public function addFlag(CommentFlag $flag): self
    {
        if (!$this->flags->contains($flag)) {
            $this->flags[] = $flag;
            $flag->setComment($this);
        }

        return $this;
    }

    public function removeFlag(CommentFlag $flag): self
    {
        if ($this->flags->removeElement($flag)) {
            // set the owning side to null (unless already changed)
            if ($flag->getComment() === $this) {
                $flag->setComment(null);
            }
        }

        return $this;
    }

    public function getDisabled(): ?bool
    {
        return $this->disabled;
    }

    public function setDisabled(bool $disabled): self
    {
        $this->disabled = $disabled;

        return $this;
    }

}
