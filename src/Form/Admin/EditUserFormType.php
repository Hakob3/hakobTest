<?php

namespace App\Form\Admin;

use App\Entity\Roles;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class EditUserFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'required' => true,
                'attr' => [
                    'class' => 'form-control'
                ],
                'constraints' => [
                    new NotBlank([], 'Should be filled')
                ]
            ])
//            ->add('roles')
            ->add('password', PasswordType::class, [
                'label' => 'New Password',
                'required' => true,
                'attr' => [
                    'class' => 'form-control'
                ],
                'constraints' => [
                    new NotBlank([], 'Should be filled')
                ]
            ])
            ->add('roles', EntityType::class, [
                'label' => 'Role',
                'class' => Roles::class,
                'mapped' => false,
                'choice_label' => 'displayName',
                'attr' => [
                    'class' => 'form-control'
                ],
            ])
            ->add('fullName',  TextType::class,[
                'label' => 'Full name',
                'required' => false,
                'attr' => [
                    'class' => 'form-control'
                ],
            ])
            ->add('phone', TextType::class,[
                'label' => 'Phone',
                'required' => false,
                'attr' => [
                    'class' => 'form-control'
                ],
            ])
            ->add('address', TextType::class,[
                'label' => 'Address',
                'required' => false,
                'attr' => [
                    'class' => 'form-control'
                ],
            ])
            ->add('zipcode', IntegerType::class,[
                'label' => 'Zipcode',
                'required' => false,
                'attr' => [
                    'class' => 'form-control'
                ],
            ])
            ->add('isVerified', CheckboxType::class, [
                'label' => 'Is verified',
                'required' => false,
                'attr' => [
                    'class' => 'form-check-input',
                ],
                'label_attr' => [
                    'class' => 'form-check-label'
                ]
            ])
            ->add('isDeleted', CheckboxType::class, [
                'label' => 'Is deleted',
                'required' => false,
                'attr' => [
                    'class' => 'form-check-input',
                ],
                'label_attr' => [
                    'class' => 'form-check-label'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
