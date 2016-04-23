<?php

namespace AppBundle\Form\Admin\Type;

use AppBundle\Entity\Admin\AdminProfile;
use AppBundle\Form\Security\Type\PlainPasswordType;
use AppBundle\Form\Security\Type\UserType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdminProfileType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, ['label' => 'admin.profile_admin.fields.first_name'])
            ->add('lastName', TextType::class, ['label' => 'admin.profile_admin.fields.last_name'])
            ->add('email', EmailType::class, ['label' => 'admin.profile_admin.fields.email']);

        if (!$options['is_me_edit']) {
            $builder->add(
                'user',
                UserType::class,
                ['is_edit' => $options['is_edit']]
            );
        }
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => AdminProfile::class,
                'is_edit' => false,
                'is_me_edit' => false,
                'validation_groups' => ['Default', 'creating'],
            ]
        );
    }

}