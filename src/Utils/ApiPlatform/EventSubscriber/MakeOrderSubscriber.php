<?php

namespace App\Utils\ApiPlatform\EventSubscriber;

use ApiPlatform\Symfony\EventListener\EventPriorities;
use App\Entity\Order;
use App\Entity\StaticStorage\OrderStaticStorage;
use App\Entity\User;
use App\Utils\Manager\OrderManager;
use JetBrains\PhpStorm\ArrayShape;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Security;

/**
 * Class MakeOrderSubscriber
 * @package App\Utils\ApiPlatform\EventSubscriber
 */
class MakeOrderSubscriber implements EventSubscriberInterface
{
    /** @var Security */
    private Security $security;

    /** @var OrderManager */
    private OrderManager $orderManager;

    /**
     * MakeOrderSubscriber constructor.
     * @param Security $security
     * @param OrderManager $orderManager
     */
    public function __construct(Security $security, OrderManager $orderManager)
    {
        $this->security = $security;
        $this->orderManager = $orderManager;
    }

    /**
     * @param ViewEvent $viewEvent
     */
    public function makeOrder(ViewEvent $viewEvent)
    {
        $order = $viewEvent->getControllerResult();
        $method = $viewEvent->getRequest()->getMethod();

        /** @var User $user */
        $user = $this->security->getUser();

        $contentJson = $viewEvent->getRequest()->getContent();
        $content = json_decode($contentJson, true);

        if (
            !$order instanceof Order ||
            Request::METHOD_POST !== $method ||
            !$user ||
            !$contentJson ||
            !array_key_exists('cartId', $content)
        ) {
            return;
        }

        $order->setOwner($user);
        $cartId = (int)$content['cartId'];

        $this->orderManager->addOrderProductsFromCart($order, $cartId);
        $this->orderManager->recalculateTotalPrice($order);
        $order->setStatus(OrderStaticStorage::ORDER_STATUS_CREATED);
    }

    /**
     * @return array[]
     */
    #[ArrayShape([KernelEvents::VIEW => "array"])]
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::VIEW => [
                'makeOrder',
                EventPriorities::PRE_WRITE
            ]
        ];
    }
}