<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass="App\Repository\PartenaireRepository")
 */
class Partenaire
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"list"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"list"})
     * @Groups({"find"})
     */
    private $entreprise;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"list"})
     */
    private $adresse;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"list"})
     */
    private $tel;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"list"})
     */
    private $status;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\User", mappedBy="partenaire")
     * @Groups({"list"})
     * 
     */
    private $users;

    /**
     * @ORM\Column(type="string", length=255,unique=true)
     * @Groups({"list"})
     * @Groups({"find"})
     */
    private $ninea;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Compts", mappedBy="partenaire")
     *
     */
    private $compts;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->compts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEntreprise(): ?string
    {
        return $this->entreprise;
    }

    public function setEntreprise(string $entreprise): self
    {
        $this->entreprise = $entreprise;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getTel(): ?int
    {
        return $this->tel;
    }

    public function setTel(int $tel): self
    {
        $this->tel = $tel;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

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
            $user->setPartenaire($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            // set the owning side to null (unless already changed)
            if ($user->getPartenaire() === $this) {
                $user->setPartenaire(null);
            }
        }

        return $this;
    }

    public function getNinea(): ?string
    {
        return $this->ninea;
    }

    public function setNinea(string $ninea): self
    {
        $this->ninea = $ninea;

        return $this;
    }

    /**
     * @return Collection|Compts[]
     */
    public function getCompts(): Collection
    {
        return $this->compts;
    }

    public function addCompt(Compts $compt): self
    {
        if (!$this->compts->contains($compt)) {
            $this->compts[] = $compt;
            $compt->setPartenaire($this);
        }

        return $this;
    }

    public function removeCompt(Compts $compt): self
    {
        if ($this->compts->contains($compt)) {
            $this->compts->removeElement($compt);
            // set the owning side to null (unless already changed)
            if ($compt->getPartenaire() === $this) {
                $compt->setPartenaire(null);
            }
        }

        return $this;
    }
}
