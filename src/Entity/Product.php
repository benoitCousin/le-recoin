<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 */
class Product
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @Gedmo\Slug(fields={"title"})
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime_immutable")
     */
    private $created_at;

    /**
     * @ORM\Column(type="boolean")
     */
    private $active;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="products")
     * @ORM\JoinColumn(nullable=true)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Categories::class, inversedBy="products")
     * @ORM\JoinColumn(nullable=false)
     */
    private $categories;

    /**
     * @ORM\OneToMany(targetEntity=Comments::class, mappedBy="product", orphanRemoval=true)
     */
    private $comments;

    /**
     * @ORM\OneToMany(targetEntity=Images::class, mappedBy="product", orphanRemoval=true ,fetch="EAGER" , cascade={"persist"})
     */
    private $images;

    /**
     * @ORM\ManyToMany(targetEntity=user::class, inversedBy="favoris")
     */
    private $favoris;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->images = new ArrayCollection();
        $this->favoris = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getSlug(): ?string
    {
        return $this->slug;
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
    

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function getuser(): user
    {
        return $this->user;
    }

    public function setuser(user $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getCategories(): ?categories
    {
        return $this->categories;
    }

    public function setCategories(?categories $categories): self
    {
        $this->categories = $categories;

        return $this;
    }

    /**
     * @return Collection|Comments[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comments $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setProduct($this);
        }

        return $this;
    }

    public function removeComment(Comments $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getProduct() === $this) {
                $comment->setProduct(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Images[]
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Images $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->setProduct($this);
        }

        return $this;
    }

    public function removeImage(Images $image): self
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getProduct() === $this) {
                $image->setProduct(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|user[]
     */
    public function getFavoris(): Collection
    {
        return $this->favoris;
    }

    public function addFavori(user $favori): self
    {
        if (!$this->favoris->contains($favori)) {
            $this->favoris[] = $favori;
        }

        return $this;
    }

    public function removeFavori(user $favori): self
    {
        $this->favoris->removeElement($favori);

        return $this;
    }
}
