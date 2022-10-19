<?php

namespace App\Form;

use App\Form\DataTransformer\TagToArrayTransformer;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\View\ChoiceView;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TomSelectType extends AbstractType
{
    public function __construct(
        private TagToArrayTransformer $transformer
    )
    {}

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired('class');
        $resolver->setDefaults([
            'compound' => false,
            'multiple' => true
        ]);
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addModelTransformer($this->transformer);
    }

    // Overriding all default config for ChoiceType for build view
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['expanded'] = false;
        $view->vars['placeholder'] = null;
        $view->vars['placeholder_in_choices'] = false;
        $view->vars['multiple'] = true;
        $view->vars['preferred_choices'] = [];
        $view->vars['choices'] = $this->choices($form->getData());
        $view->vars['choice_translation_domain'] = false;
        $view->vars['full_name'] .= '[]';
        $view->vars['required'] = false;
    }

    // The block prefix must match with built-in field ChoiceType
    // Will render a Select HTML Element
    public function getBlockPrefix(): string
    {
        return 'choice';
    }

    private function choices(?Collection $collection)
    {
        return $collection
            ->map(function($tag) {
                return new ChoiceView($tag, (string)$tag->getId(), $tag->getName());
            })
            ->toArray();
    }
}