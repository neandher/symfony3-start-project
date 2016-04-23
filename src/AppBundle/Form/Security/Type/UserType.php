<?php

namespace AppBundle\Form\Security\Type;

use AppBundle\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends PlainPasswordType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('isEnabled', CheckboxType::class, ['required' => false, 'label' => 'user.is_enabled']);

        if (!$options['is_edit']) {

            parent::buildForm($builder,$options);
        }
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => User::class,
                'is_edit'    => false,
            ]
        );
    }

}