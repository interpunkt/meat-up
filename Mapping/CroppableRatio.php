<?php


namespace Ip\MeatUp\Mapping;

use Doctrine\Common\Annotations\Annotation\Required;

/**
 * @Annotation
 * @Target("PROPERTY")
 */
class CroppableRatio
{
    /**
     * @var float | integer
     * @Required
     */
    public $aspectRatio;
}
