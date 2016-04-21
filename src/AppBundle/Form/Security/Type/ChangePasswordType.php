<?php

namespace AppBundle\Form\Security\Type;

use AppBundle\Entity\Admin\AdminProfile;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;

class ChangePasswordType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'current_password',
                PasswordType::class,
                array(
                    'label' => 'security.change_password.fields.current_password',
                    'mapped' => false,
                    'constraints' => new UserPassword()
                )
            )
            ->add(
                'user',
                PlainPasswordType::class
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => AdminProfile::class,
                'validation_groups' => ['Default', 'changing']
            )
        );
    }
}
