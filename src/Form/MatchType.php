<?php

namespace App\Form;

use App\Entity\Joueur;
use App\Entity\Match;
use App\Repository\JoueurRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MatchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $poule = $options['poule'];
        $builder
            ->add('joueur1', EntityType::class, array(
                "class" => "App\Entity\Joueur",
                "query_builder" => function (JoueurRepository $joueurRepository) use ($poule) {
                    return $joueurRepository->getQBJoueursParPoule($poule);
                },
            ))->add('joueur2', EntityType::class, array(
                "class" => "App\Entity\Joueur",
                "query_builder" => function (JoueurRepository $joueurRepository) use ($poule) {
                    return $joueurRepository->getQBJoueursParPoule($poule);
                },
            ))->add('joueur1Gagne', SubmitType::class)
            ->add('joueur2Gagne', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Match::class,
        ]);
        $resolver->setRequired(['poule']);
    }
}
