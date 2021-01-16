<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class CategoryController extends AbstractController
{
    private $urlGenerator;

    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    public function index()
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
            return $this->redirectToRoute('app_home');
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

            return $this->redirectToRoute('category_index');
        }

        return $this->render('category/edit.html.twig', [
            'category' => $category,
            'categories' => $categories,
            'form' => $form->createView()
        ]);
    }

    public function remove(Category $category)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($category);
        $em->flush();

        return $this->redirectToRoute('category_index');
    }
}
