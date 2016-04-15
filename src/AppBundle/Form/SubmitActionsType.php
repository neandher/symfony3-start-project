<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SubmitActionsType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (in_array(SubmitActions::SAVE_AND_CLOSE, $options['actions'])) {
            $builder->add(SubmitActions::SAVE_AND_CLOSE, SubmitType::class, ['label' => 'form.submit_actions.save_and_close']);
        }

        if (in_array(SubmitActions::SAVE_AND_NEW, $options['actions'])) {
            $builder->add(SubmitActions::SAVE_AND_NEW, SubmitType::class, ['label' => 'form.submit_actions.save_and_new']);
        }

        if (in_array(SubmitActions::SAVE_AND_KEEP, $options['actions'])) {
            $builder->add(SubmitActions::SAVE_AND_KEEP, SubmitType::class, ['label' => 'form.submit_actions.save_and_keep']);
        }

    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'actions' => []
            ]
        );
    }

}