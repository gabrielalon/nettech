<?php

namespace App\Application\Form\Transaction;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PercentType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class PercentDiscountType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                $builder
                    ->create(
                        'value',
                        PercentType::class,
                        [
                            'label' => 'Wartość rabatu',
                            'help'  => 'Wartość rabatu procentowego może wynosić o 1% do 100%',
                            'type'  => 'fractional',
                        ]
                    )
            )
            ->add('submit', SubmitType::class, ['label' => 'Rabatuj']);
    }
}
