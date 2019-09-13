<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TarifsRepository")
 */
class Tarifs
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
    private $borneInferieure;

    /**
     * @ORM\Column(type="integer")
     */
    private $borneSuperieure;

    /**
     * @ORM\Column(type="integer")
     */
    private $valeur;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Transaction", mappedBy="tarif")
     */
    private $transactions;

    public function __construct()
    {
        $this->transactions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBorneInferieure(): ?int
    {
        return $this->borneInferieure;
    }

    public function setBorneInferieure(int $borneInferieure): self
    {
        $this->borneInferieure = $borneInferieure;

        return $this;
    }

    public function getBorneSuperieure(): ?int
    {
        return $this->borneSuperieure;
    }

    public function setBorneSuperieure(int $borneSuperieure): self
    {
        $this->borneSuperieure = $borneSuperieure;

        return $this;
    }

    public function getValeur(): ?int
    {
        return $this->valeur;
    }

    public function setValeur(int $valeur): self
    {
        $this->valeur = $valeur;

        return $this;
    }

    /**
     * @return Collection|Transaction[]
     */
    public function getTransactions(): Collection
    {
        return $this->transactions;
    }

    public function addTransaction(Transaction $transaction): self
    {
        if (!$this->transactions->contains($transaction)) {
            $this->transactions[] = $transaction;
            $transaction->setTarif($this);
        }

        return $this;
    }

    public function removeTransaction(Transaction $transaction): self
    {
        if ($this->transactions->contains($transaction)) {
            $this->transactions->removeElement($transaction);
            // set the owning side to null (unless already changed)
            if ($transaction->getTarif() === $this) {
                $transaction->setTarif(null);
            }
        }

        return $this;
    }
}