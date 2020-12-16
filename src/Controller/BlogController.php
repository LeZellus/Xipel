<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class BlogController extends AbstractController
{
    /**
     * @Route("/blog", name="blog")
     */
    public function index(): Response
    {
        return $this->render('blog/index.html.twig');
    }

    /**
     * @Route("/blog/{url}", name="blog_show")
     */
    public function show($url)
    {
        return $this->render('blog/show.html.twig', [
            'slug' => $url
        ]);
    }

    /**
     * @Route("/blog/add", priority=10, name="blog_add")
     */
    public function add(Request $request)
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $article->setUpdatedAt(new \DateTime());

            if ($article->getThumb() !== null) {
                $file = $form->get('thumb')->getData();
                $fileName = uniqid(). '.'.$file->guessExtension();

                try {
                    $file->move(
                        $this->getParameter('images_directory'),
                        $fileName
                    );
                } catch (FileException $e) {
                    return new Response($e->getMessage());
                }

                $article->setThumb($fileName);
            }

            if ($article->getIsPublished()){
                $article->setPublishedAt(new \DateTime());
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($article); //Persist Article entity
            $em->flush(); //Execute Request
            return new Response('Le formulaire a été soumis...');
        }

        return $this->render('blog/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/blog/edit/{id}", name="blog_edit", requirements={"id"="\d+"})
     */
    public function edit(Article $article)
    {
        return $this->render('blog/edit.html.twig', [
            'article' => $article
        ]);
    }

    /**
     * @Route("/remove/{id}", name="blog_remove", requirements={"id"="\d+"})
     */
    public function remove($id)
    {
        return new Response('<h1>Supprimer l\'article' . $id . '</h1>');
    }
}
