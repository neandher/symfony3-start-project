<?php

namespace AppBundle\Helper;

use Doctrine\ORM\EntityManager;

class ClassFieldsNameHelper
{

    public static function getFieldsName(EntityManager $em, $entityClassName)
    {
        $metaData = $em->getClassMetadata($entityClassName);
        
        $fields = $metaData->getFieldNames();
        $associations = $metaData->getAssociationMappings();

        foreach ($associations as $assoc_ind => $assoc_val) {

            $associationFields = $em->getClassMetadata($assoc_val['targetEntity'])->getFieldNames();

            foreach ($associationFields as $val) {
                $fields[] = $assoc_ind . '.' . $val;
            }
        }
        
        return $fields;
    }
}