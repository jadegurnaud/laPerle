<?php

namespace App\Form;

use App\Entity\Commande;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Form\ProduitNewType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommandeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('total')
            ->add('client_id', EntityType::class, [
                'class' => 'App\Entity\Client',
                'choice_label' => 'nom',
            ]);
            if (!$options['isUpdate']) {
                // Only add produits field for non-update operations
                $builder->add('produits', EntityType::class, [
                    'class' => 'App\Entity\Produit',
                    'choice_label' => function ($produit) {
                        // Customize the label based on the $produit entity
                        return $produit->getProduitTypeLibelle()->getLibelle() . ' - ' . $produit->getId();
                    },
                    'multiple' => true,
                    'expanded' => true,
                ])
                ;
            } else {
                $builder->add('livraison_id', LivraisonType::class)
                // Other fields...
                ->add('paiement', PaiementType::class);
            }
            
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Commande::class,
            'selectedProduits' => [],
            'isUpdate' => false, // Add this option
        ]);
        $resolver->setAllowedTypes('isUpdate', 'bool');
    }
}
