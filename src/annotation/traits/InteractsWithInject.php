<?php

namespace iszsw\mock\annotation\traits;

use Doctrine\Common\Annotations\Reader;
use PhpDocReader\PhpDocReader;
use iszsw\mock\annotation\illustrate\Inject;
use ReflectionObject;
use think\App;

/**
 * 通过app@make反射实现的类、方法 可以使用注解自动注入参数
 *
 * Trait InteractsWithInject
 * @package iszsw\mock\annotation\traits
 * @property App    $app
 * @property Reader $reader
 */
trait InteractsWithInject
{
    protected $docReader = null;

    protected function getDocReader()
    {
        if (empty($this->docReader)) {
            $this->docReader = new PhpDocReader();
        }

        return $this->docReader;
    }

    protected function autoInject()
    {
        if ($this->app->config->get('mock.inject.enable', true)) {
            $this->app->resolving(function ($object) {

                if ($this->isInjectClass(get_class($object))) {

                    $reader = $this->getDocReader();

                    $refObject = new ReflectionObject($object);

                    foreach ($refObject->getProperties() as $refProperty) {
                        if ($refProperty->isDefault() && !$refProperty->isStatic()) {
                            $annotation = $this->reader->getPropertyAnnotation($refProperty, Inject::class);
                            if ($annotation) {
                                if ($annotation->value) {
                                    $value = $this->app->make($annotation->value);
                                } else {
                                    //获取@var类名
                                    $propertyClass = $reader->getPropertyClass($refProperty);
                                    if ($propertyClass) {
                                        $value = $this->app->make($propertyClass);
                                    }
                                }

                                if (!empty($value)) {
                                    if (!$refProperty->isPublic()) {
                                        $refProperty->setAccessible(true);
                                    }
                                    $refProperty->setValue($object, $value);
                                }
                            }
                        }
                    }

                    if ($refObject->hasMethod('__injected')) {
                        $this->app->invokeMethod([$object, '__injected']);
                    }
                }
            });
        }
    }

    protected function isInjectClass($name)
    {
        $namespaces = ['app' => 'app\\'] + $this->app->config->get('mock.inject.namespaces', []);

        foreach ($namespaces as $namespace) {
            $namespace = rtrim($namespace, '\\') . '\\';

            if (0 === stripos(rtrim($name, '\\') . '\\', $namespace)) {
                return true;
            }
        }
    }
}
