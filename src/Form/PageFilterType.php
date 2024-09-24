<?php

namespace App\Form;

use App\Entity\Blog;
use App\Entity\BlogCollection;
use App\Entity\Category;
use App\Filter\PageFilter;
use App\Form\DataTransformer\TagTransformer;
use App\Repository\CategoryRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Event\PreSubmitEvent;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use function Sodium\add;

class PageFilterType extends AbstractType
{

  public function __construct(private TagTransformer $tagTransformer)
  {
  }

  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $builder
      ->setMethod('GET')
      ->add('title', TextType::class, ['required' => false])
      ->add('body', TextType::class, ['required' => false]);
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => PageFilter::class,
      //'csrf_protection' => false,
    ]);
  }
}
