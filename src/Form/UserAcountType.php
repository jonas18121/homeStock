<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

class UserAcountType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('lastName',       TextType::class)
            ->add('firstName',      TextType::class)
            ->add('phoneNumber',    TelType::class, [
                'label' => 'Téléphone',
                'required' => false,
            ])
            ->add('email',          EmailType::class)
            // ->add('images')
        ;
    }

    /**
     * https://symfony.com/doc/current/form/validation_groups.html
     * https://symfony.com/doc/4.4/validation/groups.html
     * On cree un groupe de validation 'validation_groups' => ['update_user'], 
     * pour pouvoir modifier le user sans le mot de passe
     * uniquement les champs qui sont dans UserAcountType seront pris en compte 
     *
     * @param OptionsResolver $resolver
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'validation_groups' => ['update_user'],
        ]);
    }
}
