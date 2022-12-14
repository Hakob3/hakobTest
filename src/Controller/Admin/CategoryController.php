<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Form\DTO\EditCategoryModel;
use App\Form\Admin\EditCategoryFormType;
use App\Form\Handler\CategoryFormHandler;
use App\Repository\CategoryRepository;
use App\Utils\Manager\CategoryManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

#[Route('/admin/category', name: 'admin_category_')]
class CategoryController extends AbstractController
{
    #[Route('/list', name: 'list')]
    public function list(CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findBy(['isDeleted' => false], ['id' => 'DESC']);

        return $this->render('admin/category/list.html.twig', [
            'categories' => $categories,
        ]);
    }

    #[Route('/edit/{id}', name: 'edit')]
    #[Route('/add', name: 'add')]
    public function edit(Request $request,CategoryFormHandler $categoryFormHandler, Category $category = null): Response
    {
        $categoryModel = EditCategoryModel::makeFromCategory($category);

        $form = $this->createForm(EditCategoryFormType::class, $categoryModel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $category = $categoryFormHandler->processEditForm($categoryModel);

            $this->addFlash('success', 'Your changes were saved!');

            return $this->redirectToRoute('admin_category_edit', ['id' => $category->getId()]);
        }

        if($form->isSubmitted() && !$form->isValid()){
            $this->addFlash('warning', 'Something went wrong. Please check your form');
        }

        return $this->renderForm('admin/category/edit.html.twig', [
            'form' => $form,
            'category' => $category

        ]);
    }

    #[Route('/delete/{id}', name: 'delete')]
    public function delete(Category $category, CategoryManager $categoryManager): Response
    {
        $categoryManager->remove($category);

        $this->addFlash('warning', 'The category was successfully deleted!');

        return $this->redirectToRoute('admin_category_list');
    }

}
