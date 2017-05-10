<?php

namespace Ip\MeatUp\Mapping;

/**
 * @Annotation
 * @Target("PROPERTY")
 */
class CKEditor
{
    /**
     * @var string
     * @Required
     */
    public $config;
}