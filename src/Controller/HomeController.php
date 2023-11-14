<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Client;
use App\Repository\ClientRepository;
use App\Form\ClientType;

class HomeController extends AbstractController
{
    #[Route('/', name: 'accueil')]
    public function index(ClientRepository $clientRepository): Response
    {
        $clients = $clientRepository->findAll();
        return $this->render('home/index.html.twig', [
            'clients' => 'clients',
        ]);
    }
}