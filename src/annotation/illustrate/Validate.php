<?php

namespace iszsw\mock\annotation\illustrate;

use Doctrine\Common\Annotations\Annotation;

/**
 * Class Validate
 * @package iszsw\mock\annotation\illustrate
 * @Annotation
 * @Annotation\Target({"METHOD"})
 */
final class Validate extends Annotation
{
    /**
     * @var string
     */
    public $scene;

    /**
     * @var array
     */
    public $message = [];

    /**
     * @var bool
     */
    public $batch = true;
}
