<?php

namespace App\Form;

use App\Entity\Blog;
use App\Entity\BlogMeta;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BlogMetaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('description', TextType::class, [
              'required' => true,
              'help' => 'Заполните мета description',
            ])
            ->add('keywords', TextType::class, [
              'required' => true,
              'help' => 'Заполните мета keywords',
            ])
            ->add('author', TextType::class, [
              'required' => true,
              'help' => 'Заполните мета author',
            ])


//            ->add('blog', EntityType::class, [
//                'class' => Blog::class,
//                'choice_label' => 'id',
//            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => BlogMeta::class,
        ]);
    }
}
