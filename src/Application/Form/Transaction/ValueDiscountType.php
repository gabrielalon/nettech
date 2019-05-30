<?php

namespace App\Application\Form\Transaction;

use Brick\Money\Money;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ValueDiscountType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                $builder
                    ->create(
                        'amount',
                        MoneyType::class,
                        [
                            'currency'   => 'PLN',
                            'data'       => $options['defaultAmount'],
                            'data_class' => null,
                            'label'      => 'Wartość rabatu',
                            'help'       => 'Wartość rabatu nie może przekraczać wartości transakcji',
                        ]
                    )
                    ->addModelTransformer(
                        new CallbackTransformer(
                        static function (Money $amount): string {
                            return $amount->getAmount()->toBigDecimal()->__toString();
                        },
                        static function (string $amount): Money {
                            return Money::of($amount, 'PLN');
                        }
                    )
                    )
            )
            ->add('submit', SubmitType::class, ['label' => 'Rabatuj']);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired('defaultAmount');
    }
}
