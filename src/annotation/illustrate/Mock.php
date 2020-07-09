<?php

namespace iszsw\mock\annotation\illustrate;

use Doctrine\Common\Annotations\Annotation;

/**
 * 测试数据
 *
 * https://github.com/fzaninotto/Faker
 *
 * @package iszsw\mock\annotation\illustrate
 * @Annotation
 * @Target({"METHOD","CLASS"})
 */
final class Mock extends MockBase
{

    /**
     * 示例类型
     *
     * 1、name
     * 2、{"date" : ["Y-m-d", "now"]}
     *
     * @var array|string
     */
    public $example;

}
