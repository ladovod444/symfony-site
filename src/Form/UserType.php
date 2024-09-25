<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $builder
      //->add('test', TextType::class, [ ])
      ->add('email', EmailType::class, [])
      ->add('password', RepeatedType::class, [])
      ->add('roles', ChoiceType::class, [
        'choices' => [
          'ROLE_ADMIN' => 'ROLE_ADMIN',
          'ROLE_USER' => 'ROLE_USER',
        ],
        'expanded' => true,
        'multiple' => true,
      ])
    ;

    $a = 1;
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => User::class,
    ]);
  }
}
