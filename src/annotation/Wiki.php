<?php

namespace iszsw\mock\annotation;

use Doctrine\Common\Annotations\Reader;
use iszsw\mock\annotation\traits\WikiWithRoute;
use think\App;

/**
 *
 * Class Wiki
 *
 * @package iszsw\mock\annotation
 * Author: zsw zswemail@qq.com
 */
class Wiki
{
    use WikiWithRoute;

    /**
     * 缓存tag
     */
    const CACHE_WIKI_TAG = 'api:wikiTag';

    /**
     * @var App
     */
    protected $app;

    /**
     * @var Reader
     */
    protected $reader;

    public function __construct(App $app, Reader $reader)
    {
        $this->app    = $app;
        $this->reader = $reader;
    }

    protected function initialize()
    {
        $this->reader = $this->app->get(Reader::class);
    }

    public function lists(): ?array
    {
        return $this->getRouteList();
    }

}