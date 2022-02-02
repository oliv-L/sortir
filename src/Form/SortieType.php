<?php

namespace App\Form;

use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Entity\Ville;
use App\Repository\LieuRepository;
use App\Repository\VilleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SortieType extends AbstractType
{
    private $entityManager;
    private $lieuRepository;
    private $villeRepository;


    /**
     * @param EntityManagerInterface $entityManager
     * @param LieuRepository $lieuRepository
     * @param VilleRepository $villeRepository
     */
    public function __Construct(EntityManagerInterface $entityManager,
                                VilleRepository $villeRepository,
                                LieuRepository $lieuRepository)
    {
        $this->entityManager = $entityManager;
        $this->lieuRepository = $lieuRepository;
        $this->villeRepository = $villeRepository;

    }

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
            //->add('lieu', EntityType::class, ['class'=>Lieu::class])
            ->add('ville', EntityType::class, ['class'=>Ville::class, 'choice_label'=>'nom', 'mapped'=>false, 'placeholder'=>'choisir une ville'])
            ->add('lieu',EntityType::class, ['class'=>Lieu::class, 'choice_label'=>'nom','choices'=>[],'attr'=>['id'=>'lieu', 'name'=>'lieu']])

            ->addEventListener(FormEvents::PRE_SUBMIT, array($this, 'onPreSubmit'))
            ->addEventListener(FormEvents::PRE_SET_DATA, array($this, 'onPreSetData'))
            ->add('save', SubmitType::class, ['label' => 'Enregistrer', 'attr'=>['class'=>"btn btn-lg btn-secondary"]])
            ->add('saveAndAdd', SubmitType::class, [
                'label' => 'Publier la sortie',
                'attr'=>['class'=>"btn btn-lg btn-secondary"]])
        ;
    }


  protected function addElements(FormInterface $form, Ville $ville = null)
  {
      $form->add('ville', EntityType::class, array(
          'required' => true,
          'mapped'=>false,
          'data' => $ville,
          'choice_label'=>'nom',
          'placeholder' => 'choisir une ville',
          'class'=>Ville::class
      ));

      $lieux = array();


      if ($ville) {
          $lieux = $this->lieuRepository->getLieu($ville->getId());

      }

      $form->add('lieu', EntityType::class, array(
          'required'=>true,
          'placeholder'=>'choisir une ville d\'abord',
          'class'=>Lieu::class,
          'choices'=>$lieux
      ));
  }

  function onPreSubmit(FormEvent $event)
  {
      $form = $event->getForm();
      $data = $event->getData();
      $ville =$this->villeRepository->find($data['ville']);
      $this->addElements($form, $ville);
  }

  function onPreSetData(FormEvent $event)
  {
      $sortie = $event->getData();
      $form = $event->getForm();

      $ville = null;
      $this->addElements($form, $ville);
  }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
            'attr' =>['novalidate'=>'novalidate']
        ]);
    }
}
