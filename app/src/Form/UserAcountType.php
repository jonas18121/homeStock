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

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class UserAcountType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('lastName', TextType::class)
            ->add('firstName', TextType::class)
            ->add('phoneNumber', TelType::class, [
                'label' => 'Téléphone',
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Cette valeur ne doit pas être vide.',
                        'groups' => 'update_user',
                    ]),
                    new Regex([
                        'pattern' => '/^(?:(?:\+|00)33|0)[12345679](?:\d{2}){4}$/',
                        'message' => 'Le format est inccorrecte et pas de numéro commençant par 08 ou +338, format valide : 0701010101 ou +33701010101',
                        'groups' => 'update_user',
                    ]),
                ],
            ])
            ->add('email', EmailType::class)
            // ->add('images')
        ;
    }

    /**
     * https://symfony.com/doc/current/form/validation_groups.html
     * https://symfony.com/doc/4.4/validation/groups.html
     * On cree un groupe de validation 'validation_groups' => ['update_user'],
     * pour pouvoir modifier le user sans le mot de passe
     * uniquement les champs qui sont dans UserAcountType seront pris en compte.
     *
     * Si on enlève 'validation_groups' => ['update_user'],
     * il ce peut que le mot de passe soit appeler
     *
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
