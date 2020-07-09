<?php

namespace iszsw\mock\controller;

use app\BaseController;
use iszsw\mock\annotation\Wiki as WikiAnnotation;
use think\App;

/**
 * 文档控制器
 *
 * @package iszsw\mock\controller
 * Author: zsw zswemail@qq.com
 */
class Wiki extends BaseController
{

    const CACHE_WIKI_PREFIX = 'api:wiki:';

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
        $static =  $this->app->config->get('mock.wiki.static');
        $mockKey =  $this->app->config->get('mock.mock.key');
        ob_start();
        include __DIR__ . '\template\index.html';
        $content = ob_get_clean();
        return $content;
    }

}