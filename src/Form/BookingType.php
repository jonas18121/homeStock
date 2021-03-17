<?php

namespace App\Form;

use App\Entity\Booking;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // ->add('dateStartAt', DateType::class)
            ->add('dateStartAt', DateType::class, [
                // 'widget' => 'single_text',
                'widget' => 'choice',
                // 'html5' => false
                'days' => range(date('d'), date('d') + 7),
                'months' => [date('m')],
                'years' => [date('y')]

            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Booking::class,
        ]);
    }
}
