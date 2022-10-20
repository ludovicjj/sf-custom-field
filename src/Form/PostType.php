<?php

namespace App\Form;

use App\Entity\Post;
use App\Entity\Tag;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class PostType extends AbstractType
{
    public function __construct(private UrlGeneratorInterface $urlGenerator)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('content')
            ->add('tags', TomSelectType::class, [
                'class' => Tag::class,
                'search' => $this->urlGenerator->generate('api_tag_search')
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Post::class
        ]);
    }
}