<?php

namespace Ip\MeatUp\Mapping;

/**
 * @Annotation
 * @Target("PROPERTY")
 */
class CKEditor
{
    /**
     * @var array
     * @Required
     */
    public $config;
}