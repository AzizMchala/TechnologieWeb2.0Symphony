<?php

namespace App\Form;

use App\Entity\Book;
use App\Entity\Author;
use App\Entity\Reader;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class)
            ->add('publicationDate', DateType::class, [
                'widget' => 'single_text',
            ])
            ->add('enabled', CheckboxType::class, [
                'required' => false,
            ])
            ->add('author', EntityType::class, [
                'class' => Author::class,
                'choice_label' => 'username',
            ])
            ->add('readers', EntityType::class, [
                'class' => Reader::class,
                'choice_label' => 'username',
                'multiple' => true,
                'expanded' => false,
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
        ]);
    }
}
