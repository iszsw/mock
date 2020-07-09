<?php

namespace iszsw\mock\annotation\illustrate;

use Doctrine\Common\Annotations\Annotation;

abstract class MockBase extends Annotation
{

    /**
     * 请求类型
     * @Enum({"request","response"})
     * @var string
     */
    public $mode = "request";

    /**
     * 名称
     * @var string
     */
    public $title;

    /**
     * 必须
     * @var bool
     */
    public $require = true;

    /**
     * 类型
     * @Enum({"string", "integer", "boolean", "array", "double"})
     * @var string
     */
    public $type = 'string';

    /**
     * 描述
     * @var string
     */
    public $description;

}
