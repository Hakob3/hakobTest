<?php

namespace App\Entity;

use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\Repository\OrderProductRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Class OrderProduct
 * @package App\Entity
 */
#[ApiResource(
    operations: [
        new Get(
            normalizationContext: ['groups' => 'orderProduct:item'],
        ),
        new GetCollection(
            normalizationContext: ['groups' => 'orderProduct:list'],
        ),
        new Post(
            normalizationContext: ['groups' => 'orderProduct:list:write'],
            security: "is_granted('ROLE_ADMIN')",
        ),
        new Delete(
            security: "is_granted('ROLE_ADMIN')",
        )
    ]
)]
#[ORM\Entity(repositoryClass: OrderProductRepository::class)]
class OrderProduct
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['orderProduct:list', 'orderProduct:item', 'order:item'])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'orderProducts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Order $appOrder = null;

    #[ORM\ManyToOne(inversedBy: 'orderProducts')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['order:item'])]
    private ?Product $product = null;

    #[ORM\Column]
    #[Groups(['orderProduct:list', 'orderProduct:item', 'order:item'])]
    private ?int $quantity = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 6, scale: 2)]
    #[Groups(['orderProduct:list', 'orderProduct:item', 'order:item'])]
    private ?string $pricePerOne = null;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Order|null
     */
    public function getAppOrder(): ?Order
    {
        return $this->appOrder;
    }

    /**
     * @param Order|null $appOrder
     * @return $this
     */
    public function setAppOrder(?Order $appOrder): self
    {
        $this->appOrder = $appOrder;

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

    /**
     * @return string|null
     */
    public function getPricePerOne(): ?string
    {
        return $this->pricePerOne;
    }

    /**
     * @param string $pricePerOne
     * @return $this
     */
    public function setPricePerOne(string $pricePerOne): self
    {
        $this->pricePerOne = $pricePerOne;

        return $this;
    }
}
