<?php

namespace iszsw\mock\annotation\illustrate;

use Doctrine\Common\Annotations\Annotation;

/**
 * 文档
 *
 * @package iszsw\mock\annotation\illustrate
 * @Annotation
 * @Annotation\Target({"METHOD"})
 */
final class WikiItem extends WikiMenu
{

    /**
     * 描述
     * @var string
     */
    public $description;

}
