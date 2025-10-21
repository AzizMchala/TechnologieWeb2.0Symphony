<?php

namespace App\Form;

use App\Entity\Author;
use App\Entity\Book;
use App\Entity\Reader;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre du livre',
                'attr' => ['class' => 'form-control']
            ])
            ->add('publicationDate', DateType::class, [
                'label' => 'Date de publication',
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control']
            ])
            ->add('published', CheckboxType::class, [
                'label' => 'Publié',
                'required' => false,
                'attr' => ['class' => 'form-check-input']
            ])
            ->add('author', EntityType::class, [
                'class' => Author::class,
                'choice_label' => 'username',
                'label' => 'Auteur',
                'attr' => ['class' => 'form-control']
            ])
            ->add('readers', EntityType::class, [
                'class' => Reader::class,
                'choice_label' => 'username',
                'label' => 'Lecteurs',
                'multiple' => true,
                'expanded' => false,
                'required' => false,
                'attr' => ['class' => 'form-control']
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
