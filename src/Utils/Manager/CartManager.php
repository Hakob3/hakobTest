<?php

namespace App\Utils\Manager;

use App\Entity\Cart;
use App\Repository\CartRepository;

/**
 * Class CartManager
 * @package App\Utils\Manager
 */
class CartManager extends AbstractBaseManager
{
    /**
     * @return CartRepository
     */
    public function getRepository(): CartRepository
    {
        return $this->entityManager->getRepository(Cart::class);
    }
}