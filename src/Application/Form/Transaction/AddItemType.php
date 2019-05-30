<?php

namespace App\Application\Form\Transaction;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class AddItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'sku',
                TextType::class,
                [
                    'attr' => [
                        'autocomplete' => 'off',
                        'class'        => 'basicAutoComplete',
                    ],
                ]
            )
            ->add('quantity', NumberType::class)
            ->add(
                'submit',
                SubmitType::class,
                [
                    'label' => 'Dodaj do transakcji',
                ]
            );
    }
}
