<?php

namespace App\Form;

use App\Entity\Campus;
use App\Model\FiltreSortie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class SearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('campus', EntityType::class, ['class' => Campus::class, 'choice_label' => 'nom', 'required' => false])
            ->add('search', TextType::class, ['required' => false])
            ->add('dateMin', DateTimeType::class,
                ['html5' => true,
                    'widget' => 'single_text', 'required' => false])
            ->add('dateMax', DateTimeType::class,
                ['html5' => true,
                    'widget' => 'single_text', 'required' => false])
            ->add('organisateur', CheckboxType::class, ['label' => 'Sorties dont je suis l\'organisateur(trice)', 'required' => false])
            ->add('inscrit', CheckboxType::class, ['label' => 'Sorties auxquelles je suis incrit(te)', 'required' => false])
            ->add('nonInscrit', CheckboxType::class, ['label' => 'Sorties auxquelles je ne suis pas incrit(te)', 'required' => false])
            ->add('sortiePassee', CheckboxType::class, ['label' => 'Sorties passées', 'required' => false]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => FiltreSortie::class
        ]);
    }
}
