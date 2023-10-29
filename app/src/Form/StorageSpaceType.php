<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Form;

use App\Entity\Category;
use App\Entity\StorageSpace;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class StorageSpaceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'attr' => [
                    'placeholder' => 'Titre ',
                ],
            ])
            ->add('description', TextareaType::class, [
                'attr' => [
                    'placeholder' => 'Description ',
                ],
            ])
            ->add('adresse', TextType::class, [
                'attr' => [
                    'placeholder' => 'Adresse ',
                ],
            ])
            ->add('postalCode', TextType::class, [
                'attr' => [
                    'placeholder' => 'Code postale ',
                ],
            ])
            ->add('city', TextType::class, [
                'attr' => [
                    'placeholder' => 'Ville ',
                ],
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'multiple' => false,
                'label' => 'Type d\'espace ',
                'required' => true,
            ])
            ->add('space', IntegerType::class, [
                'attr' => [
                    'placeholder' => 'Espace en m2 ',
                ],
            ])
            ->add('priceByDays', MoneyType::class, [
                'divisor' => 100,
                'attr' => [
                    'placeholder' => 'Prix en euros par jours',
                ],
            ])
            ->add('imageFile', VichImageType::class, [
                'required' => false,
                'label' => 'Image mise en avant',
                'allow_delete' => false, // pas obligatoire, sur true par defaut, si on le met sur false le checkbox de suppression disparait
                'download_uri' => false, // pas obligatoire, sur true par defaut, si on le met sur false le lien de téléchargement disparait
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => StorageSpace::class,
        ]);
    }
}
