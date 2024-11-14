<?php

namespace App\Form;

use App\Entity\Blog;
use App\Entity\BlogCollection;
use App\Entity\Category;
use App\Entity\User;
use App\Form\DataTransformer\TagTransformer;
use App\Repository\CategoryRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints\Choice;

class BlogType extends AbstractType
{

  public function __construct(
    private readonly TagTransformer $tagTransformer,
    private readonly Security       $security,
  )
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
      //->add('status')
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
      ])

    ->add('status', ChoiceType::class, [
      'choices' => [
        'pending' => 'pending',
        'active' => 'active',
        'blocked' => 'blocked',
      ]
    ])
    ->add('blogMeta', BlogMetaType::class, []);

    // Разрешаем использовать категорию только админу
    if ($this->security->isGranted('ROLE_ADMIN')) {
      $builder->add('category', EntityType::class, [
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
      ->add('user', EntityType::class, [
        'class' => User::class,
        'query_builder' => function (UserRepository $repository) {
          return $repository->createQueryBuilder('user')->orderBy('user.email', 'DESC');
        },
        //'choice_label' => 'email', // Здесь важно исп. название св-ва для label, если не реализован метод toString
        'choice_label' => 'getEmailFormated', // Здесь можно исп. доп метод сущности User getEmailFormated()
        'required' => false,
        'empty_data' => null,
        'placeholder' => '-- Пользователь --' // Добавление "пустой" категории в список
        //'choices' => $group->getUsers(),
      ])
          ->add('updated_at', DateTimeType::class, [
              'widget' => 'single_text',
          ])
      ;

//        $builder->add('startDateTime', DateTimeType::class, [
//            'date_label' => 'Starts On',
//        ]);
    }

    $builder->get('tags')
      ->addModelTransformer($this->tagTransformer);

  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => Blog::class,
      'csrf_protection' => false,
    ]);
  }
}
