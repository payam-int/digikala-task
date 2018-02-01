<?php

namespace App\Form;

use App\Entity\ProductAdvancedSearch;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductAdvancedSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('searchQuery', TextType::class, [
                'label' => 'Keywords',
                'required' => false
            ])
            ->add('searchFields', ChoiceType::class, [
                'expanded' => true,
                'multiple' => true,
                'label' => 'Search inside',
                'choices' => ['Title' => 'title', 'Description' => 'description', 'Color' => 'variants.color']
            ])
            ->add('minPrice', IntegerType::class, array(
                'label' => 'Minimum price',
                'attr' => array(
                    'min' => 0,
                    'step' => 0.01
                ),
                'required' => false
            ))
            ->add('maxPrice', IntegerType::class, array(
                'label' => 'Maximum price',
                'attr' => array(
                    'min' => 0,
                    'step' => 0.01
                ),
                'required' => false
            ));

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // uncomment if you want to bind to a class
            //'data_class' => AdvancedSearch::class,
        ]);
    }
}
