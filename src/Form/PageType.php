<?php

namespace App\Form;

use App\Entity\Page;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use App\Form\DataTransformer\TagTransformer;

class PageType extends AbstractType
{
  public function __construct(private TagTransformer $tagTransformer)
  {
  }

  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $builder
      ->add('title')
      ->add('body')
      ->add('status')
      ->add('author')
      ->add('tags', TextType::class, [
        'label' => 'Теги',
        'required' => false,
      ])
    ->add('pageMeta', PageMetaType::class, []);
    $builder->get('tags')
      ->addModelTransformer($this->tagTransformer);
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => Page::class,
      'csrf_protection' => false,
    ]);
  }
}
