<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Product;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\Count;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\PositiveOrZero;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom de l\'objet',
                'constraints' => [
                    new NotBlank(message: 'Le nom est obligatoire.'),
                    new Length(min: 2, max: 100, minMessage: 'Le nom doit contenir au moins {{ limit }} caractères.'),
                ],
            ])
            ->add('color', TextType::class, ['label' => 'Couleur'])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'attr'  => ['rows' => 5],
            ])
            ->add('material', TextType::class, ['label' => 'Matériau'])
            ->add('style', TextType::class, ['label' => 'Style / Époque'])
            ->add('origin', TextType::class, ['label' => 'Origine'])
            ->add('stock', IntegerType::class, [
                'label' => 'Stock disponible',
                'constraints' => [
                    new PositiveOrZero(message: 'Le stock ne peut pas être négatif.'),
                ],
            ])
            ->add('size', TextType::class, ['label' => 'Dimensions'])
            ->add('weight', TextType::class, ['label' => 'Poids'])
            ->add('price', MoneyType::class, [
                'label'    => 'Prix',
                'currency' => 'EUR',
                'constraints' => [
                    new PositiveOrZero(message: 'Le prix ne peut pas être négatif.'),
                ],
            ])
            ->add('category', EntityType::class, [
                'label'        => 'Nom de la catégorie',
                'class'        => Category::class,
                'choice_label' => 'name',
                'placeholder'  => 'Choisissez une catégorie',
                'constraints'  => [new NotBlank(message: 'La catégorie est obligatoire.')],
            ])

            ->add('imageFiles', FileType::class, [
                'label'    => 'Photos de la pièce',
                'mapped'   => false,
                'multiple' => true,
                'required' => false,
                'attr'     => ['accept' => 'image/jpeg,image/png,image/webp'],
                'constraints' => [
                    new Count(max: 5),
                    // PROTECTION 1 : validation du type MIME et de la taille
                    new All([
                        new Image(
                            maxSize: '1M',
                            maxSizeMessage: 'Chaque image ne doit pas dépasser 2 Mo.',
                            mimeTypes: ['image/jpeg', 'image/png', 'image/webp'],
                            mimeTypesMessage:'Formats acceptés : JPG, PNG, WebP.',
                        ),
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
