<?php

namespace Ip\MeatUp\Generator;

use Ip\MeatUp\Util\FormImportUtil;
use Ip\MeatUp\Util\AnnotationUtil;
use Ip\MeatUp\Twig\MeatUpTwig;

class FormGenerator
{
    /**
     * @var AnnotationUtil
     */
    private $annotationUtil;
    /**
     * @var FormImportUtil
     */
    private $formImportUtil;
    /**
     * @var MeatUpTwig
     */
    private $meatUpTwig;
    private $entityBundleNameSpace;
    private $vichFileNameProperties;

    public function __construct(MeatUpTwig $meatUpTwig, AnnotationUtil $annotationUtil, FormImportUtil $formImportUtil, $entityBundleNameSpace) {
        $this->meatUpTwig = $meatUpTwig;
        $this->annotationUtil = $annotationUtil;
        $this->formImportUtil = $formImportUtil;
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
                    $this->annotationUtil->get('ManyToOne', 'targetEntity', $property);

                if ($this->annotationUtil->has('ManyToOneChoice', $property)) {
                    $field['choiceLabels'] = $this->annotationUtil->get('ManyToOneChoice', 'labels', $property);
                }

                if ($this->annotationUtil->has('ManyToOneOrderBy', $property)) {
                    $field['orderByNames'] = $this->annotationUtil->get('ManyToOneOrderBy', 'names', $property);
                    $field['orderByDirections'] = $this->annotationUtil->get(
                        'ManyToOneOrderBy', 'directions', $property);
                }
            } elseif ('number' === $type) {
                $scale = $this->annotationUtil->get('Column', 'scale', $property);
                if ($scale !== false) {
                    $field['scale'] = $scale;
                }
            } elseif ('ckeditor' === $type) {
                $config = $this->annotationUtil->get('CKEditor', 'config', $property);
                if (!empty($config)) {
                    $field['config'] = $config;
                }
            } elseif ('vichUploadable' === $type) {
                $fileNameProperty = $this->annotationUtil->get('VichUploadable', 'fileNameProperty', $property);
                $this->vichFileNameProperties[] = $fileNameProperty;
            }

            $fields[$name] = $field;
        }

        $fields = $this->removeVichFileNameProperties($fields);

        foreach ($fields as $field) {
            $import = $this->formImportUtil->getImport($field['type']);

            if (!in_array($import, $imports)) {
                $imports[] = $import;
            }
        }

        sort($imports);

        $twig = $this->meatUpTwig->get();

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
        if ($this->annotationUtil->has('Id', $property)) {
            return true;
        }

        if ($this->annotationUtil->has('Ignore', $property)) {
            return true;
        }

        return false;
    }

    private function removeVichFileNameProperties($fields)
    {
        foreach ($this->vichFileNameProperties as $fileNameProperty) {
            if (array_key_exists($fileNameProperty, $fields)) {
                unset($fields[$fileNameProperty]);
            }
        }

        return $fields;
    }
}
