<?php

namespace AppBundle\Form\Security\Type;

use AppBundle\Entity\AbstractProfile;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ResettingResetType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'user',
                PlainPasswordType::class
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => AbstractProfile::class,
                'validation_groups' => ['Default', 'resetting']
            )
        );
    }
}
