<?php

namespace App\Form;

use App\DataTransfertObject\UserDto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', TextType::class)
            ->add('lastname', TextType::class)
            ->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
                $user = $event->getData();
                $form = $event->getForm();
                if (!$user) {
                    return;
                }

                if (isset($user['firstname']) && $user['lastname']) {
                    $form->add('password', PasswordType::class);

                    // Remove default submit button
                    $form->remove('Save');

                    // Add new submit button
                    $form->add('SaveAndAdd',SubmitType::class, [
                        'attr' => ['class' => 'save btn btn-success'],
                        'label' => 'Save Custom'
                    ]);
                }
            })
            ->add('Save', SubmitType::class, [
                'attr' => ['class' => 'save btn btn-success'],
                'label' => 'Save Button'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => UserDto::class,
            'empty_data' => function (FormInterface $form) {
                return new UserDto(
                    $form->get('firstname')->getData(),
                    $form->get('lastname')->getData(),
                    null
                );
            },
        ]);
    }
}