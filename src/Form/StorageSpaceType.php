<?php

namespace App\Form;

use App\Entity\StorageSpace;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class StorageSpaceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'attr' => [
                    'placeholder' => 'Titre '
                ]
            ])
            ->add('description', TextareaType::class, [
                'attr' => [
                    'placeholder' => 'Description '
                ]
            ])
            ->add('adresse', TextType::class, [
                'attr' => [
                    'placeholder' => 'Adresse '
                ]
            ])
            ->add('postalCode', TextType::class, [
                'attr' => [
                    'placeholder' => 'Code postale '
                ]
            ])
            ->add('city', TextType::class, [
                'attr' => [
                    'placeholder' => 'Ville '
                ]
            ])
            ->add('type', TextType::class, [
                'attr' => [
                    'placeholder' => 'Type d\'espace '
                ]
            ])
            ->add('space', TextType::class, [
                'attr' => [
                    'placeholder' => 'Espace en m2 '
                ]
            ])
            ->add('priceByDays', MoneyType::class, [
                'attr' => [
                    'placeholder' => 'Prix par jours'
                ]
            ])
            ->add('images')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => StorageSpace::class,
        ]);
    }
}
