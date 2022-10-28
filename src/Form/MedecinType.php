<?php

namespace App\Form;

use App\Entity\Departement;
use App\Entity\Medecin;
use App\Entity\Region;
use App\Entity\Ville;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MedecinType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('region', EntityType::class, [
                'class' => Region::class,
                'placeholder' => 'Choose a region',
                'mapped' => false,
                'required' => false
            ]);
        $builder->get('region')->addEventListener(FormEvents::POST_SUBMIT, function(FormEvent $event) {
            $form = $event->getForm();
            $this->addDepartementField($form->getParent(), $form->getData());

        });
        $builder->addEventListener(FormEvents::POST_SET_DATA, function(FormEvent $event) {
            $data = $event->getData();
            $form = $event->getForm();
            $ville = $data->getVille();
            if ($ville) {
                $departement = $ville->getDepartement();
                $region = $departement->getRegion();
                $this->addDepartementField($form, $region);
                $this->addVilleField($form, $departement);
                $form->get('region')->setData($region);
                $form->get('departement')->setData($departement);
            } else {
                $this->addDepartementField($form, null);
                $this->addVilleField($form, null);
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Medecin::class
        ]);
    }

    /**
     * @param FormInterface $form Parent of region form
     * @param Region|null $region
     * @return void
     */
    private function addDepartementField(FormInterface $form, ?Region $region)
    {
        // create builder for field
        $builder = $form->getConfig()->getFormFactory()->createNamedBuilder(
            'departement',
            EntityType::class,
            null,
            [
                'class' => Departement::class,
                'placeholder' => 'Choose a departement',
                'mapped' => false,
                'required' => false,
                'auto_initialize' => false, // important
                'choices' => $region ? $region->getDepartements() : [],
            ]
        );

        // Add event
        $builder->addEventListener(FormEvents::POST_SUBMIT, function(FormEvent $event) {
            $form = $event->getForm();
            $departement = $form->getData();
            if ($departement !== null) {
                $this->addVilleField($form->getParent(), $departement);
            }
        });

        $form->add($builder->getForm());

    }

    /**
     * @param FormInterface $form
     * @param Departement|null $departement
     * @return void
     */
    private function addVilleField(FormInterface $form, ?Departement $departement)
    {
        $form->add('ville', EntityType::class, [
            'class' => Ville::class,
            'placeholder' => 'Choose a town',
            'choices' => $departement ? $departement->getVilles() : []
        ]);
    }
}