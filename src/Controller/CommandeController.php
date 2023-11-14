<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Commande;
use App\Entity\Paiement;
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
        $form = $this->createForm(CommandeType::class, $commande, ['isUpdate' => false]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $selectedProducts = $form->get('produits')->getData();

            foreach ($selectedProducts as $product) {
                // Do something with each selected product, e.g., add it to the Commande entity
                $commande->addProduit($product);
            }


            $this->entityManager->persist($commande);
            $this->entityManager->flush();

            $paiement = new Paiement();
            $paiement->setCommandeId($commande);
            // ... (set other properties)

            // Persist the Paiement entity
            $this->entityManager->persist($paiement);
            $this->entityManager->flush();

            return $this->redirectToRoute('commande');
        }

        $clients = $clientRepository->findAll();
        return $this->render('commande/commande_add.html.twig', [
            'form' => $form->createView(),
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

        $selectedProducts = $commande->getProduits();


        $form = $this->createForm(CommandeType::class, $commande, [
            'selectedProduits' => $selectedProducts,
            'isUpdate' => true,
            'data' => $commande,
        ]);
        $form->handleRequest($request);



        if ($form->isSubmitted() && $form->isValid()) {
            // Update Livraison entity
            $livraison = $form->get('livraison_id')->getData();
            $commande->setLivraisonId($livraison);

            // Update Paiement entity
            $paiement = $form->get('paiement')->getData();
            $commande->setPaiement($paiement);

            $entityManager->persist($commande);
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
