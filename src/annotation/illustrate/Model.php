<?php

namespace iszsw\mock\annotation\illustrate;

use Doctrine\Common\Annotations\Annotation;
use Doctrine\Common\Annotations\Annotation\Target;

/**
 * 注入模型
 * @package iszsw\mock\annotation\illustrate
 * @Annotation
 * @Target({"METHOD"})
 */
final class Model extends Annotation
{
    /**
     * @var string
     */
    public $var = 'id';

    /**
     * @var boolean
     */
    public $exception = true;
}
