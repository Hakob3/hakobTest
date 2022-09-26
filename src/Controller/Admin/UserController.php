<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\Admin\AddUserFormType;
use App\Form\Admin\EditUserFormType;
use App\Form\Handler\UserFormHandler;
use App\Repository\UserRepository;
use App\Utils\Manager\UserManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class UserController
 * @package App\Controller\Admin
 */
#[Route('/admin/user', name: 'admin_user_')]
#[IsGranted('ROLE_SUPER_ADMIN')]
class UserController extends AbstractController
{
    /**
     * @param UserRepository $userRepository
     * @return Response
     */
    #[Route('/list', name: 'list')]
    public function list(
        UserRepository $userRepository
    ): Response {
        $users = $userRepository->findAll();

        return $this->render(
            'admin/user/list.html.twig',
            [
                'users' => $users
            ]
        );
    }

    /**
     * @param Request $request
     * @param UserFormHandler $userFormHandler
     * @return Response
     */
    #[Route('/add', name: 'add')]
    public function add(
        Request $request,
        UserFormHandler $userFormHandler
    ): Response {
        $user = new User();
        $form = $this->createForm(AddUserFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $userFormHandler->processEditForm($form);

            $this->addFlash('success', 'Your changes were saved!');

            return $this->redirectToRoute('admin_user_edit', ['id' => $user->getId()]);
        }

        if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('warning', 'Something went wrong. Please check your form!');
        }

        return $this->renderForm(
            'admin/user/edit.html.twig',
            [
                'user' => $user,
                'form' => $form,
            ]
        );
    }

    /**
     * @param Request $request
     * @param UserFormHandler $userFormHandler
     * @param User $user
     * @return Response
     */
    #[Route('/edit/{id}', name: 'edit')]
    public function edit(
        Request $request,
        UserFormHandler $userFormHandler,
        User $user
    ): Response {
        $form = $this->createForm(EditUserFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $userFormHandler->processEditForm($form);

            $this->addFlash('success', 'Your changes were saved!');

            return $this->redirectToRoute('admin_user_edit', ['id' => $user->getId()]);
        }

        if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('warning', 'Something went wrong. Please check your form!');
        }

        return $this->renderForm(
            'admin/user/edit.html.twig',
            [
                'user' => $user,
                'form' => $form,
            ]
        );
    }

    /**
     * @param User $user
     * @param UserManager $userManager
     * @return Response
     */
    #[Route('/delete/{id}', name: 'delete')]
    public function delete(
        User $user,
        UserManager $userManager
    ): Response {
        $userManager->remove($user);

        $this->addFlash('warning', 'The user is successfully deleted!');

        return $this->redirectToRoute('admin_user_list');
    }
}
