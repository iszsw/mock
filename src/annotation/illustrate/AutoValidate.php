<?php

namespace iszsw\mock\annotation\illustrate;

use Doctrine\Common\Annotations\Annotation;

/**
 * Class AutoValidate
 * @package iszsw\mock\annotation\illustrate
 * @Annotation
 * @Annotation\Target({"METHOD"})
 */
final class AutoValidate extends Annotation
{
    /**
     * @var array
     */
    public $rule = [];

    /**
     * @var array
     */
    public $message = [];

}
