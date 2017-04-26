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
            if ($reflection->isId($property))
            {
                continue;
            }

            $type = $reflection->getType($property);

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
                $field['label'] .= ' <sup>*</sup>';
            }

            if ($type === 'manyToOne')
            {
                $field['class'] = $entityBundleNameSpace . '\Entity\\' .
                    $reflection->getManyToOneTargetEntity($property);

                if ($reflection->hasManyToOneOrderBy($property))
                {
                    $field['orderByName'] = $reflection->getManyToOneOrderByName($property);
                    $field['orderByDirection'] = $reflection->getManyToOneOrderByDirection($property);

                    if ($field['orderByDirection'] != 'ASC' || $field['orderByDirection'] != 'DESC')
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
}