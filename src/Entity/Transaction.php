<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TransactionRepository")
 */

class   Transaction
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"list"})
     */
    private $id;

    


    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * 
     */
    private $cni;

    /**
     * @ORM\Column(type="bigint", nullable=true)
     * @Groups({"list"})
     */
    private $montant;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateEnvoie;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $codeEnvoie;

  


    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateRetrait;


    /**
     * @ORM\Column(type="bigint")
     */
    private $commissionEtat;

    /**
     * @ORM\Column(type="bigint")
     */
    private $commissionAdmin;

    /**
     * @ORM\Column(type="bigint")
     */
    private $commissionRetrait;

    /**
     * @ORM\Column(type="bigint")
     */
    private $commissionEnvoie;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="transactions")
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Agence;

    

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Retrait", inversedBy="transactions")
     * @Groups({"list"})
     */
    private $retrait;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Envoie", inversedBy="transactions")
     */
    private $envoie;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Tarifs", inversedBy="transactions")
     */
    private $tarif;

    public function getId(): ?int
    {
        return $this->id;
    }

  


    public function getCni(): ?string
    {
        return $this->cni;
    }

    public function setCni(?string $cni): self
    {
        $this->cni = $cni;

        return $this;
    }

    public function getMontant(): ?int
    {
        return $this->montant;
    }

    public function setMontant(?int $montant): self
    {
        $this->montant = $montant;

        return $this;
    }

    public function getDateEnvoie(): ?\DateTimeInterface
    {
        return $this->dateEnvoie;
    }

    public function setDateEnvoie(?\DateTimeInterface $dateEnvoie): self
    {
        $this->dateEnvoie = $dateEnvoie;

        return $this;
    }

    public function getCodeEnvoie(): ?string
    {
        return $this->codeEnvoie;
    }

    public function setCodeEnvoie(string $codeEnvoie): self
    {
        $this->codeEnvoie = $codeEnvoie;

        return $this;
    }

 

    public function getDateRetrait(): ?\DateTimeInterface
    {
        return $this->dateRetrait;
    }

    public function setDateRetrait(?\DateTimeInterface $dateRetrait): self
    {
        $this->dateRetrait = $dateRetrait;

        return $this;
    }

   

    

    public function getCommissionEtat(): ?int
    {
        return $this->commissionEtat;
    }

    public function setCommissionEtat(int $commissionEtat): self
    {
        $this->commissionEtat = $commissionEtat;

        return $this;
    }

    public function getCommissionAdmin(): ?int
    {
        return $this->commissionAdmin;
    }

    public function setCommissionAdmin(int $commissionAdmin): self
    {
        $this->commissionAdmin = $commissionAdmin;

        return $this;
    }

    public function getCommissionRetrait(): ?int
    {
        return $this->commissionRetrait;
    }

    public function setCommissionRetrait(int $commissionRetrait): self
    {
        $this->commissionRetrait = $commissionRetrait;

        return $this;
    }

    public function getCommissionEnvoie(): ?int
    {
        return $this->commissionEnvoie;
    }

    public function setCommissionEnvoie(int $commissionEnvoie): self
    {
        $this->commissionEnvoie = $commissionEnvoie;

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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getAgence(): ?string
    {
        return $this->Agence;
    }

    public function setAgence(string $Agence): self
    {
        $this->Agence = $Agence;

        return $this;
    }

    public function getEnvoie(): ?Envoie
    {
        return $this->envoie;
    }

    public function setEnvoie(?Envoie $envoie): self
    {
        $this->envoie = $envoie;

        return $this;
    }

    public function getRetrait(): ?Retrait
    {
        return $this->retrait;
    }

    public function setRetrait(?Retrait $retrait): self
    {
        $this->retrait = $retrait;

        return $this;
    }

    public function getTarif(): ?Tarifs
    {
        return $this->tarif;
    }

    public function setTarif(?Tarifs $tarif): self
    {
        $this->tarif = $tarif;

        return $this;
    }
}
