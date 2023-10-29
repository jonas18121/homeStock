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
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('lastName', TextType::class, [
                'attr' => [
                    'placeholder' => 'Votre nom ',
                ],
                'constraints' => [
                    new Callback(function ($text, $context) {
                        if ($text) {
                            $tab_text = mb_str_split($text);

                            if (isset($tab_text[3])) {
                                if ($this->searchConsonne($tab_text[0]) && $this->searchConsonne($tab_text[1]) && $this->searchConsonne($tab_text[2]) && $this->searchConsonne($tab_text[3])) {
                                    $context->addViolation('Vous êtes probablement un robot Consonne');
                                }

                                if ($this->searchVoyelle($tab_text[0]) && $this->searchVoyelle($tab_text[1]) && $this->searchVoyelle($tab_text[2]) && $this->searchVoyelle($tab_text[3])) {
                                    $context->addViolation('Vous êtes probablement un robot Voyelle');
                                }
                            }
                        }
                    }),
                ],
            ])
            ->add('firstName', TextType::class, [
                'attr' => [
                    'placeholder' => 'Votre prénom',
                ],
                'constraints' => [
                    new Callback(function ($text, $context) {
                        if ($text) {
                            $tab_text = mb_str_split($text);

                            if (isset($tab_text[3])) {
                                if ($this->searchConsonne($tab_text[0]) && $this->searchConsonne($tab_text[1]) && $this->searchConsonne($tab_text[2]) && $this->searchConsonne($tab_text[3])) {
                                    $context->addViolation('Vous êtes probablement un robot Consonne');
                                }

                                if ($this->searchVoyelle($tab_text[0]) && $this->searchVoyelle($tab_text[1]) && $this->searchVoyelle($tab_text[2]) && $this->searchVoyelle($tab_text[3])) {
                                    $context->addViolation('Vous êtes probablement un robot Voyelle');
                                }
                            }
                        }
                    }),
                ],
            ])
            ->add('email', EmailType::class, [
                'attr' => [
                    'placeholder' => 'Votre email',
                ],
            ])
            ->add('password', PasswordType::class, [
                'attr' => [
                    'placeholder' => 'Votre mot de passe',
                ],
            ])
            ->add('confirm_password', PasswordType::class, [
                'attr' => [
                    'placeholder' => 'Confirmer votre mot de passe',
                ],
            ])
        ;
    }

    public function searchConsonne(string $param): bool
    {
        $list_consonne = [
            'B', 'C', 'D', 'F', 'G', 'H', 'J', 'K', 'L', 'M', 'N', 'P', 'Q', 'R', 'S', 'T', 'V', 'W', 'X', 'Z',
            'b', 'c', 'd', 'f', 'g', 'h', 'j', 'k', 'l', 'm', 'n', 'p', 'q', 'r', 's', 't', 'v', 'w', 'x', 'z',
        ];

        if (\in_array($param, $list_consonne, true)) {
            return true;
        }

        return false;
    }

    public function searchVoyelle(string $param): bool
    {
        $list_voyelle = [
            'A', 'E', 'I', 'O', 'U', 'Y',
            'a', 'e', 'i', 'o', 'u', 'y',
        ];

        if (\in_array($param, $list_voyelle, true)) {
            return true;
        }

        return false;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
