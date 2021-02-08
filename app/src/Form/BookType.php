<?php

namespace App\Form;

use App\Entity\Book;
use App\Entity\BookTranslation;
use App\Entity\Author;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormInterface;

class BookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name_en', TextType::class, [
                'setter' => function (Book &$book, ?string $name, FormInterface $form): void {
                    $book->translate('en')->setName($name);
                },
            ])
            ->add('name_ru', TextType::class, [
                'setter' => function (Book &$book, ?string $name, FormInterface $form): void {
                    $book->translate('ru')->setName($name);
                },
            ]);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection' => false,
            'data_class' => Book::class,
        ]);
    }
}
