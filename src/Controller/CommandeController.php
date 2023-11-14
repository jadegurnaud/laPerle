<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Commande;
use App\Entity\Livraison;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\CommandeRepository;
use App\Repository\ClientRepository;
use App\Form\CommandeType;
use App\Form\LivraisonType;

class CommandeController extends AbstractController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    #[Route('/commande', name: 'commande')]
    public function index(CommandeRepository $commandeRepository): Response
    {
        $commandes = $commandeRepository->findAll();
        return $this->render('commande/index.html.twig', [
            'commandes' => $commandes,
        ]);
    }

    #[Route('/commande/add', name: 'commande_add', methods: ['POST', 'GET'])]
    public function add(Request $request, ClientRepository $clientRepository): Response
    {
        $commande = new Commande();
        $form = $this->createForm(CommandeType::class, $commande);
        $form->handleRequest($request);

        $livraison = new  Livraison();
        $formLivraison = $this->createForm(LivraisonType::class, $livraison);
        $formLivraison->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() && $formLivraison->isSubmitted() && $formLivraison->isValid()) {

            $this->entityManager->persist($commande);
            $this->entityManager->persist($livraison);
            $this->entityManager->flush();

            return $this->redirectToRoute('commande');
        }

        $clients = $clientRepository->findAll();
        return $this->render('commande/commande_add.html.twig', [
            'form' => $form->createView(),
            'formLivraison' => $formLivraison->createView(),
            'clients' => $clients
        ]);
    }

    #[Route('/commande/update/{id}', name: 'commande_update', methods: ['POST', 'GET'])]
    public function update($id, Request $request, EntityManagerInterface $entityManager): Response
    {
        $commande = $entityManager->getRepository(Commande::class)->find($id);

        if (!$commande) {
            throw $this->createNotFoundException(
                'No commande found for id ' . $id
            );
        }

        $form = $this->createForm(CommandeNewType::class, $commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('commande');
        }
       
        return $this->render('commande/update.html.twig', [
            'commande' => $commande,
            'form' => $form->createView(),
        ]);
    }




    // #[Route('/commande/delete/{id}', name: 'commande_delete', methods: ['DELETE'])]
    // public function delete($id): Response
    // {
    //     $commande = $this->entityManager->getRepository(Commande::class)->find($id);

    //     if (!$commande) {
    //         throw $this->createNotFoundException(
    //             'No commande found for id ' . $id
    //         );
    //     }

    //     $this->entityManager->remove($commande);
    //     $this->entityManager->flush();

    //     return $this->redirectToRoute('commande');
    // }
}
