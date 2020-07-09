<?php

namespace iszsw\mock;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\Common\Annotations\Reader;
use iszsw\mock\annotation\CachedReader;
use iszsw\mock\annotation\traits\InteractsWithInject;
use iszsw\mock\annotation\traits\InteractsWithModel;
use iszsw\mock\annotation\traits\InteractsWithRoute;
use iszsw\mock\annotation\traits\InteractsWithWiki;
use think\App;
use think\Service as BaseService;

class Service extends BaseService
{

    use InteractsWithRoute, InteractsWithInject, InteractsWithModel, InteractsWithWiki;

    /** @var Reader */
    protected $reader;

    public function register()
    {
        AnnotationReader::addGlobalIgnoredName('mixin');

        // TODO: this method is deprecated and will be removed in doctrine/annotations 2.0
        AnnotationRegistry::registerLoader('class_exists');

        $this->app->bind(Reader::class, function (App $app) {
            return new CachedReader(new AnnotationReader(), $app);
        });
    }

    public function boot(Reader $reader)
    {
        $this->reader = $reader;

        //注解路由
        $this->registerAnnotationRoute();

        //文档路由
        $this->registerWikiRoute();

        //自动注入
        $this->autoInject();

        //模型注解方法提示
        $this->detectModelAnnotations();
    }

}
