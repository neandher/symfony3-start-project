<?php

namespace AppBundle\Form\Admin\Type;

use Symfony\Component\OptionsResolver\OptionsResolver;

class AdminProfileUpdateType extends AdminProfileType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        
        $resolver->setDefaults(
            [
                'validation_groups' => [],
                'is_edit' => true
            ]
        );
    }
}