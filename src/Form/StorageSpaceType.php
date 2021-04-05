<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\StorageSpace;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
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
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'multiple' => false,
                'label' => 'Type d\'espace ',
                'required' => true
            ])
            ->add('space', TextType::class, [
                'attr' => [
                    'placeholder' => 'Espace en m2 '
                ]
            ])
            ->add('priceByDays', MoneyType::class, [
                'divisor' => 100,
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
