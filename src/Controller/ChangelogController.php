<?php

namespace App\Controller;

use App\Entity\Changelog;
use App\Form\ChangelogType;
use App\Repository\ChangelogRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ChangelogController extends AbstractController
{
    /**
     * @param ChangelogRepository $changelogRepository
     * @return Response
     */
    public function index(ChangelogRepository $changelogRepository): Response
    {
        return $this->render('changelog/index.html.twig', [
            'changelogs' => $changelogRepository->findAll(),
        ]);
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function add(Request $request): Response
    {
        $changelog = new Changelog();
        $form = $this->createForm(ChangelogType::class, $changelog);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $changelog->setCreatedAt(new \DateTime());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($changelog);
            $entityManager->flush();

            $this->addFlash("success", "Le changelog à été mis à jour");
            return $this->redirectToRoute('changelog_index');
        }

        return $this->render('changelog/new.html.twig', [
            'changelog' => $changelog,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Changelog $changelog
     * @return Response
     */
    public function show(Changelog $changelog): Response
    {
        return $this->render('changelog/show.html.twig', [
            'changelog' => $changelog,
        ]);
    }

    /**
     * @param Request $request
     * @param Changelog $changelog
     * @return Response
     */
    public function edit(Request $request, Changelog $changelog): Response
    {
        $form = $this->createForm(ChangelogType::class, $changelog);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash("success", "Le changelog à été modifié");
            return $this->redirectToRoute('changelog_index');
        }

        return $this->render('changelog/edit.html.twig', [
            'changelog' => $changelog,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @param Changelog $changelog
     * @return Response
     */
    public function remove(Request $request, Changelog $changelog): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($changelog);
        $entityManager->flush();

        $this->addFlash("success", "Le changelog à été supprimé");
        return $this->redirectToRoute('changelog_index');
    }
}
