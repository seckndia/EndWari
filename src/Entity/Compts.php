<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass="App\Repository\ComptsRepository")
 */
class Compts
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"list"})
     * 
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"list"})
     * @Groups({"find"})
     */
    private $numcompt;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Groups({"list"})
     * @Groups({"find"})
     */
    private $solde;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Partenaire", inversedBy="compts")
     * @Groups({"find"})
     */
    private $partenaire;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Depots", mappedBy="compt")
     */
    private $depots;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\User", mappedBy="numCompt")
     */
    private $users;

    public function __construct()
    {
        $this->depots = new ArrayCollection();
        $this->users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumcompt(): ?string
    {
        return $this->numcompt;
    }

    public function setNumcompt(string $numcompt): self
    {
        $this->numcompt = $numcompt;

        return $this;
    }

    public function getSolde(): ?float
    {
        return $this->solde;
    }

    public function setSolde(?float $solde): self
    {
        $this->solde = $solde;

        return $this;
    }

    public function getPartenaire(): ?Partenaire
    {
        return $this->partenaire;
    }

    public function setPartenaire(?Partenaire $partenaire): self
    {
        $this->partenaire = $partenaire;

        return $this;
    }

    /**
     * @return Collection|Depots[]
     */
    public function getDepots(): Collection
    {
        return $this->depots;
    }

    public function addDepot(Depots $depot): self
    {
        if (!$this->depots->contains($depot)) {
            $this->depots[] = $depot;
            $depot->setCompt($this);
        }

        return $this;
    }

    public function removeDepot(Depots $depot): self
    {
        if ($this->depots->contains($depot)) {
            $this->depots->removeElement($depot);
            // set the owning side to null (unless already changed)
            if ($depot->getCompt() === $this) {
                $depot->setCompt(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setNumCompt($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            // set the owning side to null (unless already changed)
            if ($user->getNumCompt() === $this) {
                $user->setNumCompt(null);
            }
        }

        return $this;
    }
}
