<?php

namespace App\Controller\Back;

use App\Entity\Bar;
use App\Form\BarType;
use App\Repository\BarRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/bar')]
class BarController extends AbstractController
{
    #[Route('/', name: 'bar_index', methods: ['GET'])]
    public function index(BarRepository $barRepository): Response
    {
        return $this->render('back/bar/index.html.twig', [
            'bars' => $barRepository->findAll(),
        ]);
    }

    #[Route('/{id}', name: 'bar_show', requirements: ['id' => '^\d+$'], methods: ['GET'])]
    public function show(Bar $bar): Response
    {
        return $this->render('back/bar/show.html.twig', [
           'bar' => $bar
        ]);
    }

    #[Route('/create', name: 'bar_create', methods: ['GET', 'POST'])]
    public function create(Request $request): Response
    {
        $bar = new Bar();
        $form = $this->createForm(BarType::class, $bar);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($bar);
            $em->flush();

            $this->addFlash('green', "Le bar {$bar->getName()} à bien été créé.");

            return $this->redirectToRoute('back_bar_show', [
                'id' => $bar->getId()
            ]);
        }

        return $this->render('back/bar/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/edit/{id}', name: 'bar_edit', requirements: ['id' => '^\d+$'], methods: ['GET', 'POST'])]
    public function edit(Bar $bar, Request $request): Response
    {
        $form = $this->createForm(BarType::class, $bar);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $this->addFlash('green', "Le bar {$bar->getName()} à bien été édité.");

            return $this->redirectToRoute('back_bar_show', [
                'id' => $bar->getId()
            ]);
        }

        return $this->render('back/bar/edit.html.twig', [
           'bar' => $bar,
           'form' => $form->createView()
        ]);
    }

    #[Route('/delete/{id}/{token}', name: 'bar_delete', requirements: ['id' => '^\d+$'], methods: ['GET'])]
    public function delete(Bar $bar, $token): Response
    {
        if ($this->isCsrfTokenValid('delete_bar', $token)) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($bar);
            $em->flush();

            $this->addFlash('red', "Le bar {$bar->getName()} à bien été supprimé.");

            return $this->redirectToRoute('back_bar_index');
        }

        throw new Exception('Invalid token  !!!');
    }
}
