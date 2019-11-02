<?php

namespace App\Form;

use App\Entity\Lottery;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LotteryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('LotteryNumber')
            ->add('Size')
            ->add('ticket_amount')
            ->add('jackpot')
            ->add('StartAt')
            ->add('CloseAt')
            ->add('Active')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Lottery::class,
        ]);
    }
}
