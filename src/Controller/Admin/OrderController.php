<?php

namespace App\Controller\Admin;

use App\Entity\Order;
use App\Entity\OrderProduct;
use App\Entity\StaticStorage\OrderStaticStorage;
use App\Form\Admin\EditOrderFormType;
use App\Form\Handler\OrderFormHandler;
use App\Repository\OrderRepository;
use App\Utils\Manager\OrderManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class OrderController
 * @package App\Controller\Admin
 */
#[Route('/admin/order', name: 'admin_order_')]
class OrderController extends AbstractController
{
    /**
     * @param OrderRepository $orderRepository
     * @return Response
     */
    #[Route('/list', name: 'list')]
    public function list(
        OrderRepository $orderRepository
    ): Response {
        $orders = $orderRepository->findBy(['isDeleted' => false], ['id' => 'DESC']);

        return $this->render(
            'admin/order/list.html.twig',
            [
                'orders' => $orders,
                'orderStatusChoices' => OrderStaticStorage::getOrderStatusChoices()
            ]
        );
    }

    /**
     * @param Request $request
     * @param OrderFormHandler $orderFormHandler
     * @return Response
     */
    #[Route('/add', name: 'add')]
    public function add(
        Request $request,
        OrderFormHandler $orderFormHandler
    ): Response {
        $order = new Order();

        $form = $this->createForm(EditOrderFormType::class, $order);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $order = $orderFormHandler->processEditForm($order);

            $this->addFlash('success', 'Your changes were saved!');

            return $this->redirectToRoute('admin_order_edit', ['id' => $order->getId()]);
        }

        if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('warning', 'Something went wrong. Please check your form');
        }

        return $this->renderForm(
            'admin/order/edit.html.twig',
            [
                'order' => $order,
                'form' => $form,
            ]
        );
    }

    /**
     * @param Request $request
     * @param OrderFormHandler $orderFormHandler
     * @param Order|null $order
     * @return Response
     */
    #[Route('/edit/{id}', name: 'edit')]
    public function edit(
        Request $request,
        OrderFormHandler $orderFormHandler,
        Order $order = null
    ): Response {
        $form = $this->createForm(EditOrderFormType::class, $order);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $order = $orderFormHandler->processEditForm($order);

            $this->addFlash('success', 'Your changes were saved!');

            return $this->redirectToRoute('admin_order_edit', ['id' => $order->getId()]);
        }

        if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('warning', 'Something went wrong. Please check your form');
        }

        $orderProducts = [];

        /** @var OrderProduct $orderProduct */
//        foreach ($order->getOrderProducts()->getValues() as $orderProduct) {
//            $orderProducts[] = [
//                'id' => $orderProduct->getId(),
//                'product' => [
//                    'id' =>$orderProduct->getProduct()->getId(),
//                    'title' => $orderProduct->getProduct()->getTitle(),
//                    'category' => [
//                        'id' => $orderProduct->getProduct()->getCategory()->getId(),
//                        'title' => $orderProduct->getProduct()->getCategory()->getTitle()
//                    ]
//                ],
//                'quantity' => $orderProduct->getQuantity(),
//                'pricePerOne' => $orderProduct->getPricePerOne()
//            ];
//        }

        return $this->renderForm(
            'admin/order/edit.html.twig',
            [
                'order' => $order,
                'orderProducts' => $orderProducts,
                'form' => $form
            ]
        );
    }

    /**
     * @param Order $order
     * @param OrderManager $orderManager
     * @return Response
     */
    #[Route('/delete/{id}', name: 'delete')]
    public function delete(
        Order $order,
        OrderManager $orderManager
    ): Response {
        $orderManager->remove($order);

        $this->addFlash('warning', 'The order was successfully deleted!');

        return $this->redirectToRoute('admin_order_list');
    }
}
