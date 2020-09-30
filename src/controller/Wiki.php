<?php

namespace iszsw\mock\controller;

use think\App;
use app\BaseController;
use iszsw\mock\annotation\Wiki as WikiAnnotation;

/**
 * 文档控制器
 *
 * @package iszsw\mock\controller
 * Author: zsw zswemail@qq.com
 */
class Wiki extends BaseController
{

    /**
     * @var WikiAnnotation
     */
    protected $wiki;

    /**
     * @var App
     */
    protected $app;

    public function __construct(App $app)
    {
        $this->app = $app;
        $this->wiki = $this->makeWiki();
    }

    private function makeWiki()
    {
        return $this->app->invokeClass(WikiAnnotation::class);
    }

    public function index()
    {
        $data = $this->wiki->lists();
        $mockKey =  $this->app->config->get('mock.mock.key');
        ob_start();
        include __DIR__.'/view/index.html';
        $content = ob_get_clean();
        return $content;
    }

}