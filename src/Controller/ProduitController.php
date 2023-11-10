<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Produit;
use App\Repository\ProduitRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\ProduitNewType;


class ProduitController extends AbstractController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    #[Route('/produit', name: 'produit')]
    public function index(ProduitRepository $produitRepository): Response
    {
        $produits = $produitRepository->findAll();
        return $this->render('produit/index.html.twig', [
            'produits' => $produits,
        ]);
    }

    #[Route('/produit/add', name: 'produit_add', methods: ['POST', 'GET'])]
    public function add(Request $request): Response
    {
        $produit = new Produit();
        $form = $this->createForm(ProduitNewType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->entityManager->persist($produit);
            $this->entityManager->flush();

            return $this->redirectToRoute('produit');
        }

        return $this->render('produit/produit_add.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    #[Route('/produit/update/{id}', name: 'produit_update', methods: ['POST', 'GET'])]
    public function update($id, Request $request, EntityManagerInterface $entityManager): Response
    {
        $produit = $entityManager->getRepository(Produit::class)->find($id);

        if (!$produit) {
            throw $this->createNotFoundException(
                'No produit found for id ' . $id
            );
        }

        $form = $this->createForm(ProduitNewType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('produit');
        }

        return $this->render('produit/update.html.twig', [
            'produit' => $produit,
            'form' => $form->createView(),
        ]);
    }




    #[Route('/produit/delete/{id}', name: 'produit_delete', methods: ['DELETE'])]
    public function delete($id): Response
    {
        $produit = $this->entityManager->getRepository(Produit::class)->find($id);

        if (!$produit) {
            throw $this->createNotFoundException(
                'No produit found for id ' . $id
            );
        }

        $this->entityManager->remove($produit);
        $this->entityManager->flush();

        return $this->redirectToRoute('produit');
    }
}
