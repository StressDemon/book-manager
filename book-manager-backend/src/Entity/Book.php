<?php

namespace App\Entity;

use App\Repository\BookRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BookRepository::class)]
class Book
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $publicationDate = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $coverImage = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $price = "0.00";

    #[ORM\Column(length: 13, unique: true)]
    private ?string $isbn = null;

    #[ORM\Column(length: 100)]
    private ?string $language = null;

    #[ORM\Column]
    private ?int $pageCount = null;

    #[ORM\Column(length: 100)]
    private ?string $publisher = null;

    #[ORM\Column(length: 255)]
    private ?string $format = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 2, scale: 1, nullable: true)]
    private ?string $ratingAverage = null;

    #[ORM\Column]
    private ?int $downloadCount = 0;

    #[ORM\Column]
    private ?int $readCount = 0;

    /**
     * @var Collection<int, Author>
     */
    #[ORM\ManyToMany(targetEntity: Author::class, inversedBy: 'books')]
    private Collection $authors;

    /**
     * @var Collection<int, Award>
     */
    #[ORM\ManyToMany(targetEntity: Award::class, inversedBy: 'books')]
    private Collection $awards;

    /**
     * @var Collection<int, Genre>
     */
    #[ORM\ManyToMany(targetEntity: Genre::class, inversedBy: 'books')]
    private Collection $genres;

    /**
     * @var Collection<int, Review>
     */
    #[ORM\OneToMany(targetEntity: Review::class, mappedBy: 'book')]
    private Collection $reviews;

    public function __construct()
    {
        $this->authors = new ArrayCollection();
        $this->awards = new ArrayCollection();
        $this->genres = new ArrayCollection();
        $this->reviews = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getPublicationDate(): ?\DateTimeInterface
    {
        return $this->publicationDate;
    }

    public function setPublicationDate(\DateTimeInterface $publicationDate): static
    {
        $this->publicationDate = $publicationDate;

        return $this;
    }

    public function getCoverImage(): ?string
    {
        return $this->coverImage;
    }

    public function setCoverImage(?string $coverImage): static
    {
        $this->coverImage = $coverImage;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getIsbn(): ?string
    {
        return $this->isbn;
    }

    public function setIsbn(string $isbn): static
    {
        $this->isbn = $isbn;

        return $this;
    }

    public function getLanguage(): ?string
    {
        return $this->language;
    }

    public function setLanguage(string $language): static
    {
        $this->language = $language;

        return $this;
    }

    public function getPageCount(): ?int
    {
        return $this->pageCount;
    }

    public function setPageCount(int $pageCount): static
    {
        $this->pageCount = $pageCount;

        return $this;
    }

    public function getPublisher(): ?string
    {
        return $this->publisher;
    }

    public function setPublisher(string $publisher): static
    {
        $this->publisher = $publisher;

        return $this;
    }

    public function getFormat(): ?string
    {
        return $this->format;
    }

    public function setFormat(string $format): static
    {
        $this->format = $format;

        return $this;
    }

    public function getRatingAverage(): ?string
    {
        return $this->ratingAverage;
    }

    public function setRatingAverage(?string $ratingAverage): static
    {
        $this->ratingAverage = $ratingAverage;

        return $this;
    }

    public function getDownloadCount(): ?int
    {
        return $this->downloadCount;
    }

    public function setDownloadCount(int $downloadCount): static
    {
        $this->downloadCount = $downloadCount;

        return $this;
    }

    public function getReadCount(): ?int
    {
        return $this->readCount;
    }

    public function setReadCount(int $readCount): static
    {
        $this->readCount = $readCount;

        return $this;
    }

    /**
     * @return Collection<int, Author>
     */
    public function getAuthors(): Collection
    {
        return $this->authors;
    }

    public function addAuthor(Author $author): static
    {
        if (!$this->authors->contains($author)) {
            $this->authors->add($author);
        }

        return $this;
    }

    public function removeAuthor(Author $author): static
    {
        $this->authors->removeElement($author);

        return $this;
    }

    /**
     * @return Collection<int, Award>
     */
    public function getAwards(): Collection
    {
        return $this->awards;
    }

    public function addAward(Award $award): static
    {
        if (!$this->awards->contains($award)) {
            $this->awards->add($award);
        }

        return $this;
    }

    public function removeAward(Award $award): static
    {
        $this->awards->removeElement($award);

        return $this;
    }

    /**
     * @return Collection<int, Genre>
     */
    public function getGenres(): Collection
    {
        return $this->genres;
    }

    public function addGenre(Genre $genre): static
    {
        if (!$this->genres->contains($genre)) {
            $this->genres->add($genre);
        }

        return $this;
    }

    public function removeGenre(Genre $genre): static
    {
        $this->genres->removeElement($genre);

        return $this;
    }

    /**
     * @return Collection<int, Review>
     */
    public function getReviews(): Collection
    {
        return $this->reviews;
    }

    public function addReview(Review $review): static
    {
        if (!$this->reviews->contains($review)) {
            $this->reviews->add($review);
            $review->setBook($this);
        }

        return $this;
    }

    public function removeReview(Review $review): static
    {
        if ($this->reviews->removeElement($review)) {
            // set the owning side to null (unless already changed)
            if ($review->getBook() === $this) {
                $review->setBook(null);
            }
        }

        return $this;
    }
}
