<?php


namespace App\Search;

use App\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchFullType extends SearchType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
            ->add('categories', EntityType::class, ['label' => 'In category', 'class' => Category::class, 'multiple' => true, 'expanded' => true])
            ->add('submit', SubmitType::class)
        ;
    }



}
