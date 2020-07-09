<?php

namespace iszsw\mock\annotation\illustrate;

use Doctrine\Common\Annotations\Annotation;

/**
 * 包裹Mock数据
 *
 * https://github.com/fzaninotto/Faker
 *
 * @package iszsw\mock\annotation\illustrate
 * @Annotation
 *
 * @property $value 作用域范围
 * @Target({"METHOD"})
 * Author: zsw zswemail@qq.com
 */
final class MockPack extends MockBase
{

    /**
     * 数据分页长度
     *
     * @var integer
     */
    public $limit = 15;

    /**
     * 置顶 （limit > 1 时有效)
     * false：['fields'=>[["a"=>"b"], ["aa"=>"bb"]]]
     *
     * true：[["a"=>"b"], ["aa"=>"bb"]]
     *
     * @var boolean
     */
    public $main = false;

}
