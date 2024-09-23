<?php

namespace App\Form;

use App\Entity\Blog;
use App\Entity\BlogCollection;
use App\Entity\Category;
use App\Form\DataTransformer\TagTransformer;
use App\Repository\CategoryRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class BlogType extends AbstractType
{

  public function __construct(private TagTransformer $tagTransformer)
  {
  }

  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $builder
      ->add('title', TextType::class, [
        'required' => true,
        'help' => 'Заголовок поста',
        'attr' => [
          'class' => 'form-control-custom',
        ]
      ])
      ->add('description', TextareaType::class, [
        'required' => true,
      ])
      ->add('text', TextareaType::class, [
        'required' => true,
      ])
      ->add('status')
      ->add('category', EntityType::class, [
        'class' => Category::class,
        'query_builder' => function (CategoryRepository $repository) {
          return $repository->createQueryBuilder('category')->orderBy('category.name', 'DESC');
        },
        'choice_label' => 'name',
        'required' => false,
        'empty_data' => null,
        'placeholder' => '-- Выбор категории --' // Добавление "пустой" категории в список
        //'choices' => $group->getUsers(),
      ])
      ->add('blog_collection', EntityType::class, [
        'class' => BlogCollection::class,
        'choice_label' => 'name',
        'required' => false,
        'empty_data' => null
        //'choices' => $group->getUsers(),
      ])
      ->add('tags', TextType::class, [
        /* 'class' => BlogCollection::class,
        'choice_label' => 'name', */
        'label' => 'Теги',
        'required' => false,
        /* 'empty_data' => null */
        //'choices' => $group->getUsers(),
      ]);

    $builder->get('tags')
      ->addModelTransformer($this->tagTransformer);

  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => Blog::class,
      //'csrf_protection' => false,
    ]);
  }
}
