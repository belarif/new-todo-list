<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{

    #[Route('/', name: 'app_homepage', methods: ['GET'])]
    public function indexAction(): Response
    {
        return $this->render('home/index.html.twig');
    }
}

