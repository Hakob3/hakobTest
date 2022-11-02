<?php

namespace App\Entity;

use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Patch;
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
        new Patch(
            normalizationContext: ['groups' => 'cartProduct:item:write'],
        ),
        new Delete(),
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
    #[Groups(['cart-products:item', 'cart-products:list', 'cart:list', 'cart:item', 'cartProduct:item:write'])]
    private ?int $quantity = null;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Cart|null
     */
    public function getCart(): ?Cart
    {
        return $this->cart;
    }

    /**
     * @param Cart|null $cart
     * @return $this
     */
    public function setCart(?Cart $cart): self
    {
        $this->cart = $cart;

        return $this;
    }

    /**
     * @return Product|null
     */
    public function getProduct(): ?Product
    {
        return $this->product;
    }

    /**
     * @param Product|null $product
     * @return $this
     */
    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    /**
     * @param int $quantity
     * @return $this
     */
    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }
}
