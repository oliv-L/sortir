<?php

namespace App\Form;

use App\Entity\Lieu;
use App\Entity\Sortie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('dateHeureDebut',
                DateTimeType::class,
                ['html5'=>true,
                    'widget'=>'single_text'])
            ->add('duree',null,['attr'=>['min'=>0, 'max'=>300, 'step'=>15]])
            ->add('dateLimiteInscription',
                DateType::class,
                     ['html5'=>true,
                    'widget'=>'single_text'])
            ->add('nbInscriptionsMax')
            ->add('infosSortie')
            ->add('lieu', EntityType::class, ['class'=>Lieu::class, 'choice_label'=>'nom', 'attr'=>['id'=>'lieu']])
            ->add('save', SubmitType::class, ['label' => 'Enregistrer'])
            ->add('saveAndAdd', SubmitType::class, ['label' => 'Publier la sortie'])



        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
            'attr' =>['novalidate'=>'novalidate']
        ]);
    }
}
