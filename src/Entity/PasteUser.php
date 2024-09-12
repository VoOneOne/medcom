<?php

namespace App\Entity;

use App\Paste\Entity\Paste;
use App\Repository\PasteUserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PasteUserRepository::class)]
class PasteUser
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne()]
    private ?User $user = null;

    /**
     * @var Collection<int, Paste>
     */
    #[ORM\OneToMany(targetEntity: Paste::class, mappedBy: 'user')]
    private Collection $pastes;

    public function __construct()
    {
        $this->pastes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, Paste>
     */
    public function getPastes(): Collection
    {
        return $this->pastes;
    }

    public function addPaste(Paste $paste): static
    {
        if (!$this->pastes->contains($paste)) {
            $this->pastes->add($paste);
            $paste->setUser($this);
        }

        return $this;
    }

    public function removePaste(Paste $paste): static
    {
        if ($this->pastes->removeElement($paste)) {
            // set the owning side to null (unless already changed)
            if ($paste->getUser() === $this) {
                $paste->setUser(null);
            }
        }

        return $this;
    }
}
