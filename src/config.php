<?php

return [
    'route'  =>
        [ // 路由
          'enable'      => true,
          'controllers' => [], // 允许读取注解的目录
        ],
    'inject' =>
        [ // 注解
          'enable'      => true,
          'controllers' => [],
        ],
    'model'  =>
        [ // 模型
          'enable' => false,
        ],
    'mock'   =>
        [ // 数据生成
          'enable' => true,
          'key'    => 'mock', //url 携带Key参数 自动生成数据 (例值为'mock' mock请求地址为 https://zsw.ink?mock=1)
          'format' => function (array $data) { // 闭包函数 | 自定义函数名 | [Obj, method]  => 返回 \think\Response 实例
              return json(
                  [
                      'code' => 1,
                      'msg'  => '请求成功',
                      'data' => $data,
                  ]
              );
          },
        ],
    'wiki'   =>
        [ // 文档
          'enable' => true,
          'route'  =>
              [ // 自定义文档路由 option 的参数 可以自定义中间件、域名等
                'route' => "wiki", // 文档路由地址
              ],
        ],
];
