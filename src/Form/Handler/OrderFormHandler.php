<?php


namespace App\Form\Handler;

use App\Entity\Order;
use App\Utils\Manager\OrderManager;

/**
 * Class OrderFormHandler
 * @package App\Form\Handler
 */
class OrderFormHandler
{
    /** @var OrderManager  */
    private OrderManager $orderManager;

    /**
     * OrderFormHandler constructor.
     * @param OrderManager $orderManager
     */
    public function __construct(OrderManager $orderManager)
    {
        $this->orderManager = $orderManager;
    }

    /**
     * @param Order $order
     * @return Order
     */
    public function processEditForm(Order $order): Order
    {
        $this->orderManager->recalculateTotalPrice($order);
        $this->orderManager->save($order);

        return $order;
    }
}