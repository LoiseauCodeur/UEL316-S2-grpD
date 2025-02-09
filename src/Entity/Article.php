<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ArticleRepository::class)]
class Article
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Title = null;

    #[ORM\Column(length: 255)]
    private ?string $content = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $Publish_day = null;

    #[ORM\OneToMany(targetEntity: user::class, mappedBy: 'article')]
    private Collection $Author;

    #[ORM\OneToMany(targetEntity: Category::class, mappedBy: 'article')]
    private Collection $Category;

    public function __construct()
    {
        $this->Author = new ArrayCollection();
        $this->Category = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->Title;
    }

    public function setTitle(string $Title): static
    {
        $this->Title = $Title;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getPublishDay(): ?\DateTimeInterface
    {
        return $this->Publish_day;
    }

    public function setPublishDay(\DateTimeInterface $Publish_day): static
    {
        $this->Publish_day = $Publish_day;

        return $this;
    }

    /**
     * @return Collection<int, user>
     */
    public function getAuthor(): Collection
    {
        return $this->Author;
    }

    public function addAuthor(user $author): static
    {
        if (!$this->Author->contains($author)) {
            $this->Author->add($author);
            $author->setArticle($this);
        }

        return $this;
    }

    public function removeAuthor(user $author): static
    {
        if ($this->Author->removeElement($author)) {
            // set the owning side to null (unless already changed)
            if ($author->getArticle() === $this) {
                $author->setArticle(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Category>
     */
    public function getCategory(): Collection
    {
        return $this->Category;
    }

    public function addCategory(Category $category): static
    {
        if (!$this->Category->contains($category)) {
            $this->Category->add($category);
            $category->setArticle($this);
        }

        return $this;
    }

    public function removeCategory(Category $category): static
    {
        if ($this->Category->removeElement($category)) {
            // set the owning side to null (unless already changed)
            if ($category->getArticle() === $this) {
                $category->setArticle(null);
            }
        }

        return $this;
    }
    public function __toString(): string
    {
        return $this->getTitle();
    }
}
