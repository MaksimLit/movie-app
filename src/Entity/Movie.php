<?php
declare(strict_types=1);

namespace App\Entity;

use App\Entity\Traits\CreatedAtTrait;
use App\Entity\Traits\IdTrait;
use App\Repository\MovieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: MovieRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Movie
{
    use IdTrait, CreatedAtTrait;

    #[ORM\Column]
    private int $kpId;

    #[ORM\Column(length: 150)]
    private string $name;

    #[ORM\Column(type: Types::TEXT)]
    private string $description;

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    private ?int $year = null;

    #[ORM\Column(type: Types::FLOAT, nullable: true)]
    private ?float $ratingKp = null;

    #[ORM\Column(type: Types::FLOAT, nullable: true)]
    private ?float $ratingImdb = null;

    #[ORM\Column(length: 128)]
    private string $posterUrl;

    private bool $isIncluded;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'movies')]
    private Collection $viewers;

    public function __construct()
    {
        $this->viewers = new ArrayCollection();
    }

    public function getKpId(): int
    {
        return $this->kpId;
    }

    public function setKpId(int $kpId): self
    {
        $this->kpId = $kpId;

        return $this;
    }

    public function getName(): string
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

    public function setDescription(?string $description): self
    {
        $this->description = $description;

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

    public function getRatingKp(): ?float
    {
        return $this->ratingKp;
    }

    public function setRatingKp(?float $ratingKp): self
    {
        $this->ratingKp = $ratingKp;

        return $this;
    }

    public function getRatingImdb(): ?float
    {
        return $this->ratingImdb;
    }

    public function setRatingImdb(?float $ratingImdb): self
    {
        $this->ratingImdb = $ratingImdb;

        return $this;
    }

    public function getPosterUrl(): string
    {
        return $this->posterUrl;
    }

    public function setPosterUrl(string $posterUrl): self
    {
        $this->posterUrl = $posterUrl;

        return $this;
    }

    public function isIncluded(): bool
    {
        return $this->isIncluded;
    }

    public function setIsIncluded(bool $isIncluded): self
    {
        $this->isIncluded = $isIncluded;

        return $this;
    }

    /**
     * @return Collection<int, UserInterface>
     */
    public function getViewers(): Collection
    {
        return $this->viewers;
    }

    public function addViewer(UserInterface $viewer): self
    {
        if (!$this->viewers->contains($viewer)) {
            $this->viewers->add($viewer);
        }

        return $this;
    }

    public function removeViewer(UserInterface $viewer): self
    {
        $this->viewers->removeElement($viewer);

        return $this;
    }
}
