<?php

namespace App\Entity;

use App\Repository\InvoiceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InvoiceRepository::class)]
class Invoice
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'invoices')]
    #[ORM\JoinColumn(nullable: false)]
    private $user;

    #[ORM\Column(type: 'string', length: 30)]
    private $number;

    #[ORM\Column(type: 'datetime')]
    private $payment_date;

    #[ORM\Column(type: 'integer')]
    private $amount;

    #[ORM\OneToMany(mappedBy: 'invoice', targetEntity: InvoiceLine::class)]
    private $Product;

    public function __construct()
    {
        $this->invoices = new ArrayCollection();
        $this->Product = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumber(): ?string
    {
        return $this->number;
    }

    public function setNumber(string $number): self
    {
        $this->number = $number;

        return $this;
    }

    public function getPaymentDate(): ?\DateTimeInterface
    {
        return $this->payment_date;
    }

    public function setPaymentDate(\DateTimeInterface $payment_date): self
    {
        $this->payment_date = $payment_date;

        return $this;
    }

    public function getAmount(): ?int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getUser(): ?self
    {
        return $this->User;
    }

    public function setUser(?self $User): self
    {
        $this->User = $User;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getInvoices(): Collection
    {
        return $this->invoices;
    }

    public function addInvoice(self $invoice): self
    {
        if (!$this->invoices->contains($invoice)) {
            $this->invoices[] = $invoice;
            $invoice->setUser($this);
        }

        return $this;
    }

    public function removeInvoice(self $invoice): self
    {
        if ($this->invoices->removeElement($invoice)) {
            // set the owning side to null (unless already changed)
            if ($invoice->getUser() === $this) {
                $invoice->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, InvoiceLine>
     */
    public function getProduct(): Collection
    {
        return $this->Product;
    }

    public function addProduct(InvoiceLine $product): self
    {
        if (!$this->Product->contains($product)) {
            $this->Product[] = $product;
            $product->setInvoice($this);
        }

        return $this;
    }

    public function removeProduct(InvoiceLine $product): self
    {
        if ($this->Product->removeElement($product)) {
            // set the owning side to null (unless already changed)
            if ($product->getInvoice() === $this) {
                $product->setInvoice(null);
            }
        }

        return $this;
    }
}
