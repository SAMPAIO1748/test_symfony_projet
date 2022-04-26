<?php

namespace App\Form;

use App\Entity\Chambre;
use App\Entity\Commande;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommandeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date_arrivee', DateType::class, [
                "widget" => 'single_text'
            ])
            ->add('date_depart', DateType::class, [
                "widget" => 'single_text'
            ])
            ->add('prenom')
            ->add('nom')
            ->add('telephone')
            ->add('email')
            ->add('chambre', EntityType::class, [
                'class' => Chambre::class,
                'choice_label' => "titre"
            ])
            ->add('Enregistrer', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Commande::class,
        ]);
    }
}
