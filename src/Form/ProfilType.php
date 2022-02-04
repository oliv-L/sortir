<?php

namespace App\Form;

use App\Entity\Participant;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Length;


class ProfilType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
           ->add('pseudo')
           ->add('prenom')
           ->add('nom')
           ->add('telephone', null, ['required'=>false] )
           ->add('email')
           ->add('plainPassword',RepeatedType::class,[
                'required'=>false,
                'type'=>PasswordType::class,
                'invalid_message'=> ' les deux saisies doivent être identiques',
                'mapped' => false,
                'first_options'=>['label'=>'mot de passe'],
                'second_options'=>['label'=>'Confirmation'],
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Votre mot de passe doit avoir {{ limit }} characteres minimum',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('photo', FileType::class, [

                // n'est pas associé aux propriétés de l'entité
                'mapped' => false,
                'required' => false,

                // utilisation des constraints car non mappé
                'constraints' => [
                    new File([
                        'maxSize' => '10000k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png'
                        ],
                        'mimeTypesMessage' => 'Veuillez choisir une photo au format valide',
                    ])
                ],
            ])


        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Participant::class,
            'attr' =>['novalidate'=>'novalidate']
        ]);
    }
}
