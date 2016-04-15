<?php

namespace AppBundle\Form\Admin\Form;

use AppBundle\Entity\Admin\AdminProfile;
use AppBundle\Form\SubmitActions;
use AppBundle\Form\SubmitActionsType;
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
            ->add('email', EmailType::class, ['label' => 'admin.profile_admin.fields.email'])
            ->add(
                'buttons',
                SubmitActionsType::class,
                [
                    'mapped' => false,
                    'actions' => [
                        SubmitActions::SAVE_AND_CLOSE,
                        SubmitActions::SAVE_AND_NEW,
                    ]
                ]
            );
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => AdminProfile::class,
            ]
        );
    }

}