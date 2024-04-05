<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\Order2Repository;
use Doctrine\DBAL\Types\Types;


#[ORM\Entity(repositoryClass: Order2Repository::class)]
class Order2
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private $id;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Product")
     */
    private $products;

    private $quantities;

    public function __construct()
    {
        $this->products = new ArrayCollection();
        $this->quantities = [];
    }

    public function addProduct(Product $product, int $quantity): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
            $this->quantities[$product->getId()] = $quantity;
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->removeElement($product)) {
            unset($this->quantities[$product->getId()]);
        }

        return $this;
    }

    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function getQuantities(): array
    {
        return $this->quantities;
    }

    public function setQuantities(array $quantities): self
    {
        $this->quantities = $quantities;

        return $this;
    }

}