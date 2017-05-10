<?php

namespace Ip\MeatUp\Generator;

use Ip\MeatUp\Util\FormImportUtil;
use Ip\MeatUp\Util\AnnotationUtil;
use Ip\MeatUp\Twig\MeatUpTwig;

final class FormGenerator
{
    public static function generate(AnnotationUtil $annotation, $meatUpDir, $entityBundleNameSpace)
    {
        $entityClassName = $annotation->getClassShortName();

        $fields = array();
        $imports = array();


        foreach ($annotation->getProperties() as $property)
        {
            if ($annotation->hasId($property) || $annotation->hasIgnore($property))
            {
                continue;
            }

            $type = $annotation->getType($property);

            if ($type === false) {
                continue;
            }

            $field = array();

            $field['type'] = $type;
            $field['name'] = $annotation->getName($property);
            $field['required'] = $annotation->getRequired($property);
            $field['label'] = ucfirst($field['name']);

            if ($field['required'] == "true") {
                $field['label'] .= ' *';
            }

            if ($type == 'manyToOne') {
                $field['class'] = $entityBundleNameSpace . '\Entity\\' .
                    $annotation->getManyToOneTargetEntity($property);

                if ($annotation->hasManyToOneChoice($property)) {
                    $field['choiceLabels'] = $annotation->getManyToOneChoiceLabels($property);
                }

                if ($annotation->hasManyToOneOrderBy($property)) {
                    $field['orderByNames'] = $annotation->getManyToOneOrderByNames($property);
                    $field['orderByDirections'] = $annotation->getManyToOneOrderByDirections($property);
                }
            }
            elseif($type == 'number') {
                $scale = $annotation->getColumnScale($property);
                if ($scale !== false) {
                    $field['scale'] = $scale;
                }
            }
            elseif ($type == 'ckeditor') {
                $config = $annotation->getCKEditorConfig($property);
                if (!empty($config)) {
                    $field['config'] = $config;
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