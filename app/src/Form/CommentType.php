<?php

namespace App\Form;

use App\Entity\Comment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class CommentType extends AbstractType
{
    /**
     * parentid n'est pas le parent_id en BDD 
     * Le champ parentid recevera l'id du commentaire pour lequel le user veut répondre avec un autre commentaire
     * lorsqu'il aura cliquer sur un bouton répondre
     * 
     * Comment parentid n'existe pas en BDD on lui met une propriété 'mapped' => false
     * pour dire que ce champ la n'exite pas dans mon entité Comment
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('content', TextareaType::class, [
                'label' => 'Commentaire',
                'attr' => [
                    'placeholder' => 'Commentaire ',
                ]
            ])
            ->add('parentid', HiddenType::class, [
                'mapped' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
        ]);
    }
}
