<?php

namespace iszsw\mock\annotation\traits;

use iszsw\mock\annotation\illustrate\Mock;
use iszsw\mock\annotation\illustrate\MockBase;
use iszsw\mock\annotation\illustrate\MockPack;

/**
 * 解析mock
 *
 * Trait ParseMock
 *
 * @package iszsw\mock\annotation\traits
 * Author: zsw zswemail@qq.com
 */
trait ParseMock
{

    use WithMock;

    /**
     * 解析mock列表
     *
     * @param array $mocks
     * @param bool $generate 自动生成测试用例
     *
     * @return array
     * Author: zsw zswemail@qq.com
     */
    protected function parseMocks(array $mocks, $generate = true): array
    {
        /** @var $data mock列表 */
        $data = [];

        /** @var $queue 区间入栈 */
        $queue = [];

        array_push($queue, ['key'=> '', 'path'=>&$data]);
        foreach ($mocks as $mock) {
            $key = $mock->mode . ':' . $mock->value;
            $item = end($queue);

            if ($mock instanceof MockPack) {
                if ($item['key'] === $key) {
                    array_pop($queue);continue;
                }
                unset($value);
                $value = $this->parseMockPack($mock);
                $item['path']['children'][] = &$value;
                array_push($queue, ['key'=>$key, 'path'=> &$value]);
            } elseif ($mock instanceof Mock){
                $item['path']['children'][] = $this->parseMock($mock, $generate);
            }
        }
        unset($queue, $value);

        return $data['children'] ?? [];
    }

    /**
     * 解析多维mock
     * @param MockPack $mock
     * @return array
     * Author: zsw zswemail@qq.com
     */
    protected function parseMockPack(MockPack $mock): array
    {
        return array_merge(
            $this->parseMockBase($mock),
            [
                'limit' => $mock->limit,
                'main'  => $mock->main,
                'example' => $mock->limit > 0 ? "[ array ( object ) ]" : " [ object ] ",
            ]
        );
    }

    /**
     * 解析mock
     * @param Mock $mock
     * @param bool $generate

     * @return array
     * Author: zsw zswemail@qq.com
     */
    protected function parseMock(Mock $mock, $generate = true): array
    {
        if ($generate)
        {
            $example = $this->mockExample($mock->example);
        }else{
            $example = $mock->example;
        }

        return array_merge(
            $this->parseMockBase($mock),
            ['example' => $example]
        );
    }

    protected function mockExample($example)
    {
        $method = '';
        $mockParams = [];
        if (is_array($example))
        {
            foreach ($example as $k => $v)
            {
                $method = $k;
                $mockParams = (array)$v;
                break;
            }
        } else {
            $method = $example;
        }

        $example = $this->mock($method, $mockParams);
        return !is_array($example) ? $example : json_encode($example, JSON_UNESCAPED_UNICODE);
    }

    private function parseMockBase(MockBase $mock): array
    {
        return [
            'mode'  => $mock->mode,
            'type'  => $mock->type,
            'title' => $mock->title,
            'field' => $mock->value,
            'require'  => $mock->require,
            'description' => $mock->description,
        ];
    }

}