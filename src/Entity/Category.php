<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
#[UniqueEntity('name')]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50,unique: true)]
    #[Assert\Length(max:50)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'wish', targetEntity: Wish::class)]
    private Collection $wish;

    public function __construct()
    {
        $this->wish = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Wish>
     */
    public function getWish(): Collection
    {
        return $this->wish;
    }

    public function addWish(Wish $wish): self
    {
        if (!$this->wish->contains($wish)) {
            $this->wish->add($wish);
            $wish->setWish($this);
        }

        return $this;
    }

    public function removeWish(Wish $wish): self
    {
        if ($this->wish->removeElement($wish)) {
            // set the owning side to null (unless already changed)
            if ($wish->getWish() === $this) {
                $wish->setWish(null);
            }
        }

        return $this;
    }
}
