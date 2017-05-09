<?php

namespace Ip\MeatUp\Generator;

use Ip\MeatUp\Util\FormImportUtil;
use Ip\MeatUp\Util\ReflectionUtil;
use Ip\MeatUp\Twig\MeatUpTwig;

final class FormGenerator
{
    public static function generate(ReflectionUtil $reflection, $meatUpDir, $entityBundleNameSpace)
    {
        $entityClassName = $reflection->getClassShortName();

        $fields = array();
        $imports = array();


        foreach ($reflection->getProperties() as $property)
        {
            if ($reflection->hasId($property) || $reflection->hasIgnore($property))
            {
                continue;
            }

            $type = $reflection->getType($property);

            if ($type === false) {
                continue;
            }

            $field = array();

            $field['type'] = $type;
            $field['name'] = $reflection->getName($property);
            $field['required'] = $reflection->getRequired($property);
            $field['label'] = ucfirst($field['name']);

            if ($field['required'] == "true") {
                $field['label'] .= ' *';
            }

            if ($type === 'manyToOne') {
                $field['class'] = $entityBundleNameSpace . '\Entity\\' .
                    $reflection->getManyToOneTargetEntity($property);

                if ($reflection->hasManyToOneChoice($property)) {
                    $field['choiceLabels'] = $reflection->getManyToOneChoiceLabels($property);
                }

                if ($reflection->hasManyToOneOrderBy($property)) {
                    $field['orderByNames'] = $reflection->getManyToOneOrderByNames($property);
                    $field['orderByDirections'] = $reflection->getManyToOneOrderByDirections($property);
                }
            }

            $import = FormImportUtil::getImport($field['type']);

            if (!in_array($import, $imports)) {
                $imports[] = $import;
            }

            $fields[] = $field;
        }

        sort($imports);

        $twig = MeatUpTwig::get($meatUpDir);

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