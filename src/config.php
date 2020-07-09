<?php

return [
    'route' => [ // 路由
        'enable' => true,
        'controllers' => []
    ],
    'inject' => [ // 注解
        'enable' => true,
        'controllers' => []
    ],
    'model' => [ // 模型
        'enable' => false,
    ],
    'mock' => [ // 数据生成
        'enable' => true,
        'key'    => 'mock',//url 携带Key参数 自动生成数据 例 key=mock 请求url https://zsw.ink?mock=1
        'format' => '{"code":1, "msg": "请求成功", "data": {data}}', //JSON 成功返回格式 {data} 自动替换文档数据
    ],
    'wiki' => [ // 文档
        'enable' => true,
        'route'  => [ // 文档路由 option的参数
            'route' => "wiki", // 文档的url
        ],
        'static' => '/static' // 静态资源文件存放地址 可以携带域名
    ]
];
