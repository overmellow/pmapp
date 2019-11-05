<?php

namespace App\Form;

use App\Entity\TempTicket;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class TempTicketType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('TicketNumber', HiddenType::class)
            // ->add('createdAt')
            // ->add('status')
            // ->add('User')
            // ->add('Lottery')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TempTicket::class,
        ]);
    }
}
