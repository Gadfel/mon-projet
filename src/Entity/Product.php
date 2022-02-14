<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 50)]
    private $name;

    #[ORM\Column(type: 'integer')]
    private $price;

    #[ORM\Column(type: 'string', length: 255)]
    private $description;

    #[ORM\Column(type: 'integer')]
    private $quantity;

    #[ORM\Column(type: 'string', length: 50)]
    private $img;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: Machine::class)]
    private $machines;

    #[ORM\ManyToOne(targetEntity: OrderLigne::class, inversedBy: 'products')]
    #[ORM\JoinColumn(nullable: false)]
    private $Order_ligne;

    public function __construct()
    {
        $this->machines = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

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

    public function getImg(): ?string
    {
        return $this->img;
    }

    public function setImg(string $img): self
    {
        $this->img = $img;

        return $this;
    }

    /**
     * @return Collection|Machine[]
     */
    public function getMachines(): Collection
    {
        return $this->machines;
    }

    public function addMachine(Machine $machine): self
    {
        if (!$this->machines->contains($machine)) {
            $this->machines[] = $machine;
            $machine->setProduct($this);
        }

        return $this;
    }

    public function removeMachine(Machine $machine): self
    {
        if ($this->machines->removeElement($machine)) {
            // set the owning side to null (unless already changed)
            if ($machine->getProduct() === $this) {
                $machine->setProduct(null);
            }
        }

        return $this;
    }

    public function getOrderLigne(): ?OrderLigne
    {
        return $this->Order_ligne;
    }

    public function setOrderLigne(?OrderLigne $Order_ligne): self
    {
        $this->Order_ligne = $Order_ligne;

        return $this;
    }
}
