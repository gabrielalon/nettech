<?php

namespace App\Application\Form\CashRegister;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class WithdrawType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'amount',
                MoneyType::class,
                [
                    'currency' => 'PLN',
                ]
            )
            ->add('submit', SubmitType::class);
    }
}
