<?php

namespace App\Form;

use App\Entity\Lottery;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class LotteryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('LotteryNumber')
            ->add('Size')
            ->add('ticket_amount')
            ->add('jackpot')
            ->add('status', ChoiceType::class, [
                'choices'  => [
                    'Not Started' => 'unstarted',
                    'Started' => 'started',
                    'Closed' => 'closed',
                    'Completed' => 'completed'
                ],
            ])
            ->add('StartAt')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Lottery::class,
        ]);
    }
}
