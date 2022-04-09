<?php

namespace App\Controller\Front;

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
        return $this->render('front/bar/index.html.twig', [
            'bars' => $barRepository->findAll(),
        ]);
    }

    #[Route('/{id}', name: 'bar_show', requirements: ['id' => '^\d+$'], methods: ['GET'])]
    public function show(Bar $bar): Response
    {
        return $this->render('front/bar/show.html.twig', [
           'bar' => $bar
        ]);
    }
}
