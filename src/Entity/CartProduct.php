<?php

namespace App\Entity;

use ApiPlatform\Metadata\Delete;
use App\Repository\CartProductRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
    operations: [
        new Get(
            normalizationContext: ['groups' => 'cart-products:item'],
        ),
        new GetCollection(
            normalizationContext: ['groups' => 'cart-products:list'],
        ),
        new Post(
            normalizationContext: ['groups' => 'cart-products:list:write'],
            security: "is_granted('ROLE_ADMIN')",
        ),
        new Delete()
    ]
)]
#[ORM\Entity(repositoryClass: CartProductRepository::class)]
class CartProduct
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['cart-products:item', 'cart-products:list', 'cart:list', 'cart:item'])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'cartProducts')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['cart-products:item', 'cart-products:list'])]
    private ?Cart $cart = null;

    #[ORM\ManyToOne(inversedBy: 'cartProducts')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['cart-products:item', 'cart-products:list', 'cart:list', 'cart:item'])]
    private ?Product $product = null;

    #[ORM\Column]
    #[Groups(['cart-products:item', 'cart-products:list', 'cart:list', 'cart:item'])]
    private ?int $quantity = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCart(): ?Cart
    {
        return $this->cart;
    }

    public function setCart(?Cart $cart): self
    {
        $this->cart = $cart;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

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
}
