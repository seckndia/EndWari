<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass="App\Repository\DepotsRepository")
 */
class Depots
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $montant;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateDepot;

    /**
     * @ORM\Column(type="integer")
     */
    private $soldeInitial;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Compts", inversedBy="depots")
     */
    private $compt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="depots")
     */
    private $caissier;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMontant(): ?int
    {
        return $this->montant;
    }

    public function setMontant(int $montant): self
    {
        $this->montant = $montant;

        return $this;
    }

    public function getDateDepot(): ?\DateTimeInterface
    {
        return $this->dateDepot;
    }

    public function setDateDepot(\DateTimeInterface $dateDepot): self
    {
        $this->dateDepot = $dateDepot;

        return $this;
    }

    public function getSoldeInitial(): ?int
    {
        return $this->soldeInitial;
    }

    public function setSoldeInitial(int $soldeInitial): self
    {
        $this->soldeInitial = $soldeInitial;

        return $this;
    }

    public function getCompt(): ?Compts
    {
        return $this->compt;
    }

    public function setCompt(?Compts $compt): self
    {
        $this->compt = $compt;

        return $this;
    }

    public function getCaissier(): ?User
    {
        return $this->caissier;
    }

    public function setCaissier(?User $caissier): self
    {
        $this->caissier = $caissier;

        return $this;
    }
}
