<?php

namespace App\Entity;

use App\Repository\HistoryRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=HistoryRepository::class)
 */
class History
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
    private $debitAccount;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $creditAccount;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $amount;

    /**
     * @ORM\Column(type="datetime")
     */
    private $editAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDebitAccount(): ?string
    {
        return $this->debitAccount;
    }

    public function setDebitAccount(string $debitAccount): self
    {
        $this->debitAccount = $debitAccount;

        return $this;
    }

    public function getCreditAccount(): ?string
    {
        return $this->creditAccount;
    }

    public function setCreditAccount(string $creditAccount): self
    {
        $this->creditAccount = $creditAccount;

        return $this;
    }

    public function getAmount(): ?string
    {
        return $this->amount;
    }

    public function setAmount(string $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getEditAt(): ?\DateTimeInterface
    {
        return $this->editAt;
    }

    public function setEditAt(\DateTimeInterface $editAt): self
    {
        $this->editAt = $editAt;

        return $this;
    }
}
