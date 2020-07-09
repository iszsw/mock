<?php

namespace iszsw\mock\annotation\traits;

use think\App;
use think\event\RouteLoaded;
use iszsw\mock\controller\Wiki;
use Doctrine\Common\Annotations\Reader;

/**
 *
 * 文档路由注册
 *
 * Trait InteractsWithRoute
 * @package think\annotation\traits
 * @property App $app
 * @property Reader $reader
 * Author: zsw zswemail@qq.com
 */
trait InteractsWithWiki
{

    protected function registerWikiRoute()
    {
        if ($this->app->config->get('mock.wiki.enable', true)
            && ($wikiOptions = $this->app->config->get('mock.wiki.route', []))
                && ($wikiOptions['route'] ?? false)) {

            $this->app->event->listen(RouteLoaded::class, function () use ($wikiOptions) {
                $routeGroup = $this->app->route->getGroup();
                $wikiRoute = $wikiOptions['route'];
                unset($wikiOptions['route']);
                $rule = $routeGroup->addRule($wikiRoute, Wiki::class . '@index');
                $rule->option($wikiOptions);
            });

        }
    }

}