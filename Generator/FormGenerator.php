<?php

namespace DL\MeatUp\Generator;

use DL\MeatUp\Util\FormImportUtil;
use DL\MeatUp\Util\ReflectionUtil;
use DL\MeatUp\Util\TwigUtil;

final class FormGenerator
{
    public static function generate(ReflectionUtil $reflection, $meatUpDir, $entityBundleNameSpace)
    {
        $entityClassName = $reflection->getClassShortName();

        $fields = array();
        $imports = array();

        foreach ($reflection->getProperties() as $property)
        {
            if ($reflection->hasId($property))
            {
                continue;
            }

            $type = $reflection->getType($property);

            // check different Types
            if( self::isHiddenType( $property, $reflection ) )
            {
                $type = 'hidden';
            }

            if($type == "text")
            {
                if( self::isCkeditor( $property, $reflection ) == 'ckeditor' )
                {
                    $type = 'ckeditor';
                }

            }

            // check if Field is vichImage Field
            if( $type === "string" )
            {
                if( self::isVichImage( $property, $reflection ) )
                {
                    $type = 'vichImage';
                }
            }

            if ($type === false)
            {
                continue;
            }

            $field = array();

            $field['type'] = $type;
            $field['name'] = $reflection->getName($property);
            $field['required'] = $reflection->getRequired($property);
            $field['label'] = ucfirst($field['name']);

            if ($field['required'] == "true")
            {
                $field['label'] .= ' *';
            }
            if ($type === 'manyToOne')
            {
                $field['class'] = $entityBundleNameSpace . '\Entity\\' .
                    $reflection->getManyToOneTargetEntity($property);
                if ($reflection->hasManyToOneOrderBy($property))
                {
                    $field['orderByName'] = $reflection->getManyToOneOrderByName($property);
                    $field['orderByDirection'] = $reflection->getManyToOneOrderByDirection($property);
                    if ($field['orderByDirection'] != 'ASC' && $field['orderByDirection'] != 'DESC')
                    {
                        throw new \RuntimeException('property orderByDirection of annotation ManyToOneOrderBy' .
                            ' has to be either ASC or DESC for ' . $property->getName());
                    }
                }
            }
            $import = FormImportUtil::getImport($field['type']);
            if (!in_array($import, $imports))
            {
                $imports[] = $import;
            }
            $fields[] = $field;
        }
        sort($imports);
        $twig = TwigUtil::getTwigEnvironment($meatUpDir);
        $formType = $twig->render('formType.php.twig',
            array(
                'namespace' => $entityBundleNameSpace . '\Form\Type',
                'className' => $entityClassName. 'Type',
                'fields' => $fields,
                'imports' => $imports
            )
        );
        return $formType;
    }

    static protected function isCkeditor( $property, $reflection )
    {
        $properties = $reflection->getPropertyAnnotations($property);

        foreach( $properties as $value )
        {
            if($value->type == 'ckeditor' )
            {
                return 'ckeditor';
            }
        }
    }

    /*
     * Check if the @formtype::type Field from Entity has 'hidden' as value
     */
    static protected function isHiddenType( $property, $reflection )
    {
        $properties = $reflection->getPropertyAnnotations($property);

        foreach( $properties as $value )
        {
            if( isset( $value->type ))
            {
                if($value->type === 'hidden' )
                {
                    return 'dieses Feld ignorieren..';
                }
            }
        }
    }

    static protected function isVichImage( $property, $reflection )
    {
        $properties = $reflection->getPropertyAnnotations($property);

        foreach( $properties as $value )
        {
            if(isset($value->mimeTypes))
            {
                dump($value->mimeTypes);
                return 'vichImage';
            }

            if($value == 'Vich\UploadableField' )
            {
                return 'vichImage';
            }
        }
    }
}