<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\NotifierInterface;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    public function index(): Response
    {
        $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();

        return $this->render('category/index.html.twig', [
            'categories' => $categories
        ]);
    }

    public function add(Request $request)
    {
        $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($category); //Persist category entity
            $em->flush(); //Execute Request
            $this->addFlash('success', 'La catégorie à été créée');

            return $this->redirectToRoute('category_index');
        }

        return $this->render('category/add.html.twig', [
            'category' => $category,
            'categories' => $categories,
            'form' => $form->createView()
        ]);
    }

    public function edit(Category $category, Request $request)
    {
        $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();
        $currentLabel = $category->getLabel();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();
            $this->addFlash('success', 'La catégorie à été éditée');

            return $this->redirectToRoute('category_index');
        }

        return $this->render('category/edit.html.twig', [
            'category' => $category,
            'categories' => $categories,
            'form' => $form->createView()
        ]);
    }

    public function remove(Category $category, NotifierInterface $notifier): Response
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($category);
        $em->flush();

        $this->addFlash('success', 'La catégorie à été supprimée');

        return $this->redirectToRoute('category_index');
    }
}
