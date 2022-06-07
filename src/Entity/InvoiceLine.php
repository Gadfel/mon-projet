<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\InvoiceLineRepository;


#[ORM\Entity(repositoryClass: InvoiceLineRepository::class)]
class InvoiceLine
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: Product::class, inversedBy: 'invoiceLines')]
    #[ORM\JoinColumn(nullable: false)]
    private $item;

    #[ORM\Column(type: 'integer')]
    private $quantity;

    #[ORM\ManyToOne(targetEntity: Invoice::class, inversedBy: 'invoiceLines')]
    #[ORM\JoinColumn(nullable: false)]
    private $invoice;

    #[ORM\Column(type: 'string', length: 255)]
    private $Product;

    public function getId(): ?int
    {
        return $this->id;
    }
    
    public function getItem(): ?Product
    {
        return $this->item;
    }

    
    public function setProduct(?Product $item): self
    {
        $this->item = $item;

        return $this;
    }
    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getInvoice(): ?invoice
    {
        return $this->invoice;
    }

    public function setInvoice(?invoice $invoice): self
    {
        $this->invoice = $invoice;

        return $this;
    }

    public function getProduct(): ?string
    {
        return $this->Product;
    }

   

   
}
