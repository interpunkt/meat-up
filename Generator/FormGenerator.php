<?php

namespace Ip\MeatUp\Generator;

use Ip\MeatUp\Util\FormImportUtil;
use Ip\MeatUp\Util\AnnotationUtil;
use Ip\MeatUp\Twig\MeatUpTwig;

final class FormGenerator
{
    private $annotationUtil;
    private $meatUpDir;
    private $entityBundleNameSpace;
    private $vichFileNameProperties;

    public function __construct(AnnotationUtil $annotationUtil, $meatUpDir, $entityBundleNameSpace)
    {
        $this->annotationUtil = $annotationUtil;
        $this->meatUpDir = $meatUpDir;
        $this->entityBundleNameSpace = $entityBundleNameSpace;
    }

    public function generate()
    {
        $entityClassName = $this->annotationUtil->getClassShortName();

        $fields = array();
        $imports = array();
        $this->vichFileNameProperties = array();

        foreach ($this->annotationUtil->getProperties() as $property) {
            if ($this->isNotUsed($property)) {
                continue;
            }

            $type = $this->annotationUtil->getType($property);

            if ($type === false) {
                continue;
            }

            $field = array();
            $name = $this->annotationUtil->getName($property);

            $field['type'] = $type;
            $field['name'] = $name;
            $field['required'] = $this->annotationUtil->getRequired($property);
            $field['label'] = ucfirst($field['name']);

            if ($field['required'] == "true") {
                $field['label'] .= ' *';
            }

            if ('manyToOne' === $type) {
                $field['class'] = $this->entityBundleNameSpace . '\Entity\\' .
                    $this->annotationUtil->getManyToOneTargetEntity($property);

                if ($this->annotationUtil->hasManyToOneChoice($property)) {
                    $field['choiceLabels'] = $this->annotationUtil->getManyToOneChoiceLabels($property);
                }

                if ($this->annotationUtil->hasManyToOneOrderBy($property)) {
                    $field['orderByNames'] = $this->annotationUtil->getManyToOneOrderByNames($property);
                    $field['orderByDirections'] = $this->annotationUtil->getManyToOneOrderByDirections($property);
                }
            }
            elseif('number' === $type) {
                $scale = $this->annotationUtil->getColumnScale($property);
                if ($scale !== false) {
                    $field['scale'] = $scale;
                }
            }
            elseif ('ckeditor' === $type) {
                $config = $this->annotationUtil->getCKEditorConfig($property);
                if (!empty($config)) {
                    $field['config'] = $config;
                }
            }
            elseif ('vichUploadable' === $type) {
                $fileNameProperty = $this->annotationUtil->getVichUploadableFileNameProperty($property);
                $this->vichFileNameProperties[] = $fileNameProperty;
            }

            $fields[$name] = $field;
        }

        $fields = $this->removeVichFileNameProperties($fields);

        foreach ($fields as $field) {
            $import = FormImportUtil::getImport($field['type']);

            if (!in_array($import, $imports)) {
                $imports[] = $import;
            }
        }

        sort($imports);

        $twig = MeatUpTwig::get($this->meatUpDir);

        $formType = $twig->render('formType.php.twig',
            array(
                'namespace' => $this->entityBundleNameSpace . '\Form\Type',
                'className' => $entityClassName. 'Type',
                'fields' => $fields,
                'imports' => $imports
            )
        );

        return $formType;
    }

    private function isNotUsed($property)
    {
        if ($this->annotationUtil->hasId($property)) {
            return true;
        }

        if ($this->annotationUtil->hasIgnore($property)) {
            return true;
        }

        return false;
    }

    private function removeVichFileNameProperties($fields)
    {
        foreach ($fields as $key => $field) {
            if (in_array($key, $this->vichFileNameProperties)) {
                unset($fields[$key]);
            }
        }

        return $fields;
    }
}