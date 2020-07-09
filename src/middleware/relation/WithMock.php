<?php

namespace iszsw\mock\middleware\relation;

use iszsw\mock\annotation\illustrate\MockBase;
use iszsw\mock\annotation\traits\ParseMock;
use think\Request;

/**
 * 启用mock数据生成工具
 *
 * @property Request $request
 *
 * Author: zsw zswemail@qq.com
 */
trait WithMock
{
    use ParseMock;

    protected function generate()
    {
        if ($this->app->config->get('mock.mock.enable', true)) {
            if ($this->request->param($this->app->config->get('mock.mock.key', 'mock'), false)) {
                $rule = $this->request->rule();
                $name = $rule->getName();
                [$class, $method] = explode('@', $name, 2);
                $refClass = new \ReflectionClass($class);
                $refMethod = $refClass->getMethod($method);
                $annotations = app(\Doctrine\Common\Annotations\Reader::class)->getMethodAnnotations($refMethod);

                $mocks = [];
                foreach ($annotations as $annotation) {
                    if ($annotation instanceof MockBase && $annotation->mode == 'response') {
                        $mocks[] = $annotation;
                    }
                }

                return $this->format($this->generateMock($this->parseMocks($mocks, false)));
            }
        }
        return true;
    }

    private function format(array $data): array
    {
        $content = $this->app->config->get('mock.mock.format', '');
        $data = json_encode($data, JSON_UNESCAPED_UNICODE);
        $content = $content ? str_replace('{data}', $data, $content): $data;
        return json_decode($content, true);
    }

    private function generateMock($dataMock): array
    {
        $data = [];

        foreach ($dataMock as $v) {
            $value = '';
            if ($children = $v['children'] ?? null) {
                $child = [];
                if ($v['limit'] > 0) {
                    for($i = 0; $i < $v['limit']; $i++) {
                        $child[] = $this->generateMock($v['children']);
                    }
                    if ($v['main']) {
                        $data = $child;
                        continue;
                    }
                } else {
                    $child = $this->generateMock($v['children']);
                }
                $value = $child;
            }else{
                $value = $this->mockExample($v['example']);
            }

            $data[$v['field']] = $value;
        }

        return $data;
    }

}
