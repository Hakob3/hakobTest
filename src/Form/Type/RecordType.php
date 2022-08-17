<?php
namespace App\Form\Type;

use App\Entity\Geometry;
use App\Entity\Record;
use Symfony\Component\Form\AbstractType;
use App\Entity\Colors;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\Count;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

class RecordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('text', TextareaType::class)
            ->add('email', TextType::class, [
                'attr' => ['maxlength' => '255'],
            ])
            ->add('images', FileType::class, [
                'multiple' => true,
                'attr' => [
                    'accept' => '.jpg,.png',
                    'multiple' => 'multiple',
                ],
                'constraints' => [new Count([
                    'max' => 4,
                    'maxMessage' => 'you cannot upload more than {{ limit }} images'
                    ])],
                'mapped' => false,
            ])
            ->add('color', EntityType::class, [
                'class' => Colors::class,
                'choice_label' => 'color'
            ])
            ->add('geometry', EntityType::class, [
                'class' => Geometry::class,
                'choice_label' => 'geometry',
            ])
            ->add('save', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Record::class,
        ]);
    }
}