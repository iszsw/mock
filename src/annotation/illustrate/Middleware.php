<?php

namespace iszsw\mock\annotation\illustrate;

use Doctrine\Common\Annotations\Annotation;
use Doctrine\Common\Annotations\Annotation\Target;

/**
 * 路由中间件
 * @package iszsw\mock\annotation\illustrate
 * @Annotation
 * @Target({"CLASS","METHOD"})
 */
final class Middleware extends Annotation
{

}
