<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\User;
use App\Form\ArticleType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\NotifierInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class BlogController extends AbstractController
{
    public function index(): Response
    {
        $articles = $this->getDoctrine()->getRepository(Article::class)->findBy(
            ['isPublished' => true],
            ['publishedAt' => 'desc']
        );

        return $this->render('blog/index.html.twig', [
            'articles' => $articles
        ]);
    }

    public function show(Article $article)
    {
        return $this->render('blog/show.html.twig', [
            'article' => $article
        ]);
    }

    /**
     * @Route("/blog/add", priority=10, name="blog_add")
     * @IsGranted("ROLE_ADMIN")
     * @param Request $request
     * @param NotifierInterface $notifier
     * @return Response
     */
    public function add(Request $request, NotifierInterface $notifier): Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $article->setUpdatedAt(new \DateTime());

            if ($article->getThumb() !== null) {
                $file = $form->get('thumb')->getData();
                $fileName = uniqid() . '.' . $file->guessExtension();

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

            if ($article->getIsPublished()) {
                $article->setPublishedAt(new \DateTime());
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($article); //Persist Article entity
            $em->flush(); //Execute Request

            $notifier->send(new Notification('L\'article à été créé', ['browser']));

            return $this->redirectToRoute('app_home');
        }

        return $this->render('blog/add.html.twig', [
            'article' => $article,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/blog/edit/{id}", name="blog_edit", requirements={"id"="\d+"})
     * @IsGranted("ROLE_ADMIN")
     * @param Article $article
     * @param Request $request
     * @param NotifierInterface $notifier
     * @return Response
     */
    public function edit(Article $article, Request $request, NotifierInterface $notifier): Response
    {
        $currentPicture = $article->getThumb();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $article->setUpdatedAt(new \DateTime());

            if ($article->getIsPublished()) {
                $article->setPublishedAt(new \DateTime());
            }

            if ($article->getThumb() !== null && $article->getThumb() !== $currentPicture) {
                $file = $form->get('thumb')->getData();
                $fileName = uniqid() . '.' . $file->guessExtension();

                try {
                    $file->move(
                        $this->getParameter('images_directory'),
                        $fileName
                    );
                } catch (FileException $e) {
                    return new Response($e->getMessage());
                }

                $article->setThumb($fileName);
            } else {
                $article->setThumb(($currentPicture));
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($article);
            $em->flush();

            $notifier->send(new Notification('L\'article à été modifié', ['browser']));
            return $this->redirectToRoute('article_edit', ['id' => $article->getId()]);
        }

        return $this->render('blog/edit.html.twig', [
            'article' => $article,
            'form' => $form->createView()
        ]);
    }
}
