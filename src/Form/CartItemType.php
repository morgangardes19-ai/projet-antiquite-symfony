<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
use Symfony\Component\Validator\Constraints\Positive;

class CartItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('quantity', IntegerType::class, [
            'label' => false,
            'attr' => [
                'min' => 1,
                'max' => $options['max_stock'],
                'class' => 'w-20 rounded-lg border-stone-300 text-center',
            ],
            'constraints' => [
                new Positive(
                    message: 'La quantité doit être supérieure à zéro.'
                ),
                new LessThanOrEqual(
                    value: $options['max_stock'],
                    message: 'Il ne reste que {{ compared_value }} exemplaire(s).'
                ),
            ],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'max_stock' => 1,
        ]);

        $resolver->setAllowedTypes('max_stock', 'int');
    }
}
