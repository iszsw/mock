<?php

namespace iszsw\mock\annotation\traits;

use iszsw\mock\annotation\illustrate\MockBase;
use iszsw\mock\annotation\illustrate\Route;
use iszsw\mock\annotation\illustrate\WikiItem;
use iszsw\mock\annotation\illustrate\WikiMenu;
use think\App;
use ReflectionClass;
use iszsw\mock\annotation\illustrate\Group;
use Doctrine\Common\Annotations\Reader;
use iszsw\mock\annotation\illustrate\Resource;
use Symfony\Component\ClassLoader\ClassMapGenerator;

/**
 * Trait InteractsWithRoute
 *
 * @package iszsw\mock\annotation\traits
 * @property App    $app
 * @property Reader $reader
 */
trait WikiWithRoute
{
    use ParseMock;

    /**
     * @var \think\Route
     */
    protected $route;

    private $routeList = [];

    protected function getRouteList()
    {
        $this->routeList || $this->getAnnotationRoutes();
        return $this->routeList;
    }

    /**
     * 注册注解路由
     */
    private function getAnnotationRoutes()
    {
        $dirs = array_merge([$this->app->getAppPath() . $this->app->config->get('route.controller_layer')], $this->app->config->get('mock.route.controllers', []));
        foreach ($dirs as $dir) {
            if (is_dir($dir)) {
                $this->scanDir($dir);
            }
        }
    }

    private function scanDir($dir)
    {
        foreach (ClassMapGenerator::createMap($dir) as $class => $path)
        {
            ($route = $this->parse($class)) && array_push($this->routeList, $route);
        }
    }

    /**
     * 解析
     *
     * Author: zsw zswemail@qq.com
     */
    protected function parse($class) : ?array
    {
        /** @var \ReflectionClass $refClass */
        $refClass = new ReflectionClass($class);

        if ($refClass->isAbstract()) {return null;}

        $menu = $this->formatMenu($refClass->getShortName());
        $prefix  = '';
        $resourceRoute = [];

        if ($wiki = $this->reader->getClassAnnotation($refClass, WikiMenu::class))
        {
            $menu['title'] = $wiki->value;
        }

        if ($resource = $this->reader->getClassAnnotation($refClass, Resource::class))
        {
            //资源路由
            $alias = ['index' => '列表', 'create' => "创建", "edit"=>"修改","read"=>"读取","save"=>"保存","update"=>"更新","delete"=>"删除"];
            $rest = array_intersect_key($this->route->getRest(), array_flip(get_class_methods($class)));
            $prefix = $resource->value;
            foreach ($rest as $v) {
                $resourceRoute[$v[2]] = $this->formatItem( $alias[$v[2]] ?? $v[2], $v[1], $v[0]);
            }
        }

        /** @var Group $group */
        if ($group = $this->reader->getClassAnnotation($refClass, Group::class))
        {
            $prefix = $group->value;
        }

        $menu['api'] = $this->parseMethod($refClass);

        foreach ($menu['api'] as $k => &$v) {
            $prefix && $v['rule'] = $prefix . '/' . trim($v['rule']);
        }

        $menu['api'] = array_merge($resourceRoute, $menu['api']);

        return ($refClass->getShortName() === $menu['title'] && empty($menu['api'])) ? null : $menu;
    }

    /**
     * 方法
     *
     * @param \ReflectionClass $refClass
     *
     * @return array
     * Author: zsw zswemail@qq.com
     */
    private function parseMethod(\ReflectionClass $refClass)
    {
        $items = [];
        foreach ($refClass->getMethods(\ReflectionMethod::IS_PUBLIC) as $refMethod)
        {
            /** @var Route $route */
            if (!$route = $this->reader->getMethodAnnotation($refMethod, Route::class)){continue;}

            $name = $refMethod->getName();
            $item = $this->formatItem($name);
            if ($wiki = $this->reader->getMethodAnnotation($refMethod, WikiItem::class))
            {
                $item['title'] = $wiki->value;
                $item['description'] = $wiki->description;
            }

            $annotations = $this->reader->getMethodAnnotations($refMethod);

            $mocks = [];
            foreach ($annotations as $annotation) {
                if ($annotation instanceof MockBase) {
                    $mocks[] = $annotation;
                }
            }

            $item['mock']   = $this->formatMock($this->parseMocks($mocks));
            $item['rule']   = $route->value;
            $item['method'] = $route->method;
            $items[$name]   = $item;
        }

        return $items;
    }


    /**
     * 返回简单数组模式
     *
     * @param array $mocks
     * @param array $data
     * @param string $key
     *
     * @return array
     * Author: zsw zswemail@qq.com
     */
    protected function formatMock(array $mocks, $data = [], $key = '')
    {
        ($main = empty($data)) && $data = ['request'=>[], 'response'=>[], 'request_ext'=>[], 'response_ext'=>[]];
        foreach ($mocks as $m) {

            if (isset($m['children']) && $m['children']) {
                $data = $this->formatMock($m['children'], $data, $m['field']);
                unset($m['children']);
            }

            if ($main) {
                if ($m['mode'] == 'request'){
                    $data['request'][] = $m;
                }else{
                    $data['response'][] = $m;
                }
            }else{
                if ($m['mode'] == 'request'){
                    $data['request_ext'][$key][] = $m;
                }else{
                    $data['response_ext'][$key][] = $m;
                }
            }
        }
        return $data;
    }

    /**
     * 格式化
     *
     * @param $data
     *
     * @return array|null
     * Author: zsw zswemail@qq.com
     */
    private function formatArr($data): ?array
    {
        return is_array($data) ? $data : (array_filter(explode(',', $data)) ?: null);
    }


    /**
     * @param array  $app 内容
     *
     * @return array
     * Author: zsw zswemail@qq.com
     */
    private function formatApp(array $app)
    {
        return array_intersect_key($app, array_flip(['id', 'title', 'domain', 'encrypt']));
    }

    /**
     * @param string $title 标题
     * @param array  $items 内容
     *
     * @return array
     * Author: zsw zswemail@qq.com
     */
    private function formatMenu($title = '')
    {
        return [
            'title' => $title,
        ];
    }

    /**
     *
     * @param string $title     标题
     * @param string $rule      规则
     * @param string $method    请求方式
     * @param string $description      说明
     * @param string $mock      mock
     *
     * @return array
     * Author: zsw zswemail@qq.com
     */
    private function formatItem($title = '', $rule = '', $method = 'get', $description = '', $mock = null)
    {
        return [
            'title' => $title,
            'rule' => $rule,
            'method' => $method,
            'description' => $description,
            'mock' => $mock ?: ['request'=>[],'response'=>[]],
        ];
    }

}
