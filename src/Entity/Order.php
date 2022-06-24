<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`order`')]
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'datetime')]
    private $date;

    #[ORM\Column(type: 'float')]
    private $total_amount;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'orders')]
    #[ORM\JoinColumn(nullable: false)]
    private $user;

    #[ORM\ManyToOne(targetEntity: Address::class, inversedBy: 'orders')]
    #[ORM\JoinColumn(nullable: false)]
    private $address;

    #[ORM\OneToMany(mappedBy: 'Orders', targetEntity: OrderLigne::class, orphanRemoval: true)]
    private $orderLignes;

    public function __construct()
    {
        $this->orderLignes = new ArrayCollection();
    }



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate( \DateTimeInterface $date): self

    {
        $this->date = $date;

        return $this;
    }

    public function getTotalAmount(): ?int
    {
        return $this->total_amount;
    }

    public function setTotalAmount(int $total_amount): self
    {
        $this->total_amount = $total_amount;

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

    public function getAddress(): ?Address
    {
        return $this->address;
    }

    public function setAddress(?Address $address): self
    {
        $this->address = $address;

        return $this;
    }

    /**
     * @return Collection<int, OrderLigne>
     */
    public function getOrderLignes(): Collection
    {
        return $this->orderLignes;
    }

    public function addOrderLigne(OrderLigne $orderLigne): self
    {
        if (!$this->orderLignes->contains($orderLigne)) {
            $this->orderLignes[] = $orderLigne;
            $orderLigne->setOrders($this);
        }

        return $this;
    }

    public function removeOrderLigne(OrderLigne $orderLigne): self
    {
        if ($this->orderLignes->removeElement($orderLigne)) {
            // set the owning side to null (unless already changed)
            if ($orderLigne->getOrders() === $this) {
                $orderLigne->setOrders(null);
            }
        }

        return $this;
    }

    
}