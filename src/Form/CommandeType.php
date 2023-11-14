<?php

namespace App\Form;

use App\Entity\Commande;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommandeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('total')
            ->add('livraison_id', EntityType::class, [
                'class' => 'App\Entity\Livraison',
                'choice_label' => 'etat',
            ])
            ->add('client_id', EntityType::class, [
                'class' => 'App\Entity\Client',
                'choice_label' => 'nom',
            ])
            ->add('paiement', EntityType::class, [
                'class' => 'App\Entity\Paiement',
                'choice_label' => 'etat',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Commande::class,
        ]);
    }
}
