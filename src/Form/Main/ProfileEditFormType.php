<?php

namespace App\Form\Main;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class ProfileEditFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('fullName', TextType::class,[
                'label' => 'label.edit_profile_fullname'
            ])
            ->add('phone', TextType::class,[
                'label' => 'label.edit_profile_phone'
            ])
            ->add('address', TextType::class,[
                'label' => 'label.edit_profile_address'
            ])
            ->add('zipcode', IntegerType::class,[
                'label' => 'label.edit_profile_zipcode'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
