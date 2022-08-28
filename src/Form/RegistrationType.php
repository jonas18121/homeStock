<?php

namespace App\Form;

use App\Entity\User;
use Doctrine\ORM\Query\Expr\Func;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('lastName', TextType::class, [
                'attr' => [
                    'placeholder' => 'Votre nom '
                ],
                'constraints' => [
                    new Callback(function ($text, $context) {

                        if ($text) {
                            $tab_text = str_split($text);

                            if (isset($tab_text[2])) {
                                if($this->searchConsonne($tab_text[0]) && $this->searchConsonne($tab_text[1]) && $this->searchConsonne($tab_text[2])){
                                    $context->addViolation('Vous êtes probablement un robot Consonne');
                                }
    
                                if($this->searchVoyelle($tab_text[0]) && $this->searchVoyelle($tab_text[1]) && $this->searchVoyelle($tab_text[2])){
                                    $context->addViolation('Vous êtes probablement un robot Voyelle');
                                }
                            }
                        }
                    }),
                ],
            ])
            ->add('firstName', TextType::class, [
                'attr' => [
                    'placeholder' => 'Votre prénom'
                ],
                'constraints' => [
                    new Callback(function ($text, $context) {

                        if ($text) {
                            $tab_text = str_split($text);

                            if (isset($tab_text[2])) {
                                if($this->searchConsonne($tab_text[0]) && $this->searchConsonne($tab_text[1]) && $this->searchConsonne($tab_text[2])){
                                    $context->addViolation('Vous êtes probablement un robot Consonne');
                                }
    
                                if($this->searchVoyelle($tab_text[0]) && $this->searchVoyelle($tab_text[1]) && $this->searchVoyelle($tab_text[2])){
                                    $context->addViolation('Vous êtes probablement un robot Voyelle');
                                }
                            }
                        }
                    }),
                ],
            ])
            ->add('email', EmailType::class, [
                'attr' => [
                    'placeholder' => 'Votre email'
                ]
            ])
            ->add('password', PasswordType::class, [
                'attr' => [
                    'placeholder' => 'Votre mot de passe'
                ]
            ])
            ->add('confirm_password', PasswordType::class, [
                'attr' => [
                    'placeholder' => 'Confirmer votre mot de passe'
                ]
            ])
        ;
    }

    public function searchConsonne($param) {

        $list_consonne = [
            'B', 'C', 'D', 'F', 'G', 'H', 'J', 'K', 'L', 'M', 'N', 'P', 'Q', 'R', 'S', 'T', 'V', 'W', 'X', 'Z',
            'b', 'c', 'd', 'f', 'g', 'h', 'j', 'k', 'l', 'm', 'n', 'p', 'q', 'r', 's', 't', 'v', 'w', 'x', 'z'
        ];

        if (in_array($param, $list_consonne)) {
            return true;
        }
        return false;
    }

    public function searchVoyelle($param) {

        $list_voyelle = [
            'A', 'E', 'I', 'O', 'U', 'Y',
            'a', 'e', 'i', 'o', 'u', 'y'
        ];

        if (in_array($param, $list_voyelle)) {
            return true;
        }
        return false;
    }

    /**
     *
     * @param OptionsResolver $resolver
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
