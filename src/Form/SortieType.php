<?php

namespace App\Form;

use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Entity\Ville;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
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
            //todo voir pour un label avec :
            ->add('lieu',EntityType::class, ['class'=>Lieu::class, 'choice_label'=>'nom','attr'=>['id'=>'lieu', 'name'=>'lieu']])
            ->add('save', SubmitType::class, ['label' => 'Enregistrer', 'attr'=>['class'=>"btn btn-lg btn-secondary"]])
            ->add('saveAndAdd', SubmitType::class, [
                'label' => 'Publier la sortie',
                'attr'=>['class'=>"btn btn-lg btn-secondary"]])

            /*
            ->addEventListener(FormEvents::PRE_SET_DATA, array($this, 'onPreSetData'))
            ->addEventListener(FormEvents::PRE_SUBMIT, array($this, 'onPreSubmit'))
            */
        ;
    }
/*
    public function addElements(FormInterface $form, Ville $ville = null)
    {
        $form->add('Ville', EntityType::class, array(
            'required'=>true,
            'data'=>$ville,
            'placeholder'=>'choisir une ville',
            'class' => 'Villes'
        ));
    }

    $Lieu = array();
    */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
            'attr' =>['novalidate'=>'novalidate']
        ]);
    }
}
