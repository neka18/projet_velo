<?php

namespace App\Form;

use App\Entity\Advert;
use App\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Choice;

class AdvertType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', null, ['help' => 'Need some help ?'])
            ->add('description', TextareaType::class)
            ->add('price')
            ->add('bikeYear', ChoiceType::class, ['choices'=> $this->getBikeYears()])
            ->add('category', EntityType::class, ['class' => Category::class])
            ->add('gallery', GalleryType::class)
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Advert::class,
        ]);
    }

    private function getBikeYears()
    {
        $years = [];
        $startYear = 1990;
        while ($startYear <= date('Y')) {
            $years[$startYear] = $startYear;
            $startYear ++;
        }
        return $years;
    }
}
