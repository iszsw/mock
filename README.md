## 演示

接口文档地址： http://demo.mock.zsw.ink/

**测试**

请求方式：post

请求参数：{username:"zsw"}

接口地址：http://demo.mi.zsw.ink/show

Mock地址：http://demo.mi.zsw.ink/show?m=1

# 基于 ThinkPHP6 的注解路由 + 自动接口文档生成 + 自动测试数据生成

主页  [https://www.zsw.ink](https://www.zsw.ink)

github  [https://github.com/iszsw/mock](https://github.com/iszsw/mock)

gitee   [https://gitee.com/iszsw/mock](https://gitee.com/iszsw/mock)

## 使用
> 1、安装 

```composer require iszsw/mock```

> 2、添加测试代码 在 app/controller 目录下增加Test.php文件
~~~php
<?php
namespace app\controller;

use app\BaseController;
use iszsw\mock\annotation\illustrate\AutoValidate;
use iszsw\mock\annotation\illustrate\Route;
use iszsw\mock\annotation\illustrate\Mock;
use iszsw\mock\annotation\illustrate\MockPack;
use iszsw\mock\annotation\illustrate\WikiItem;
use iszsw\mock\annotation\illustrate\WikiMenu;

/**
 * @WikiMenu("测试")
 * @package app\controller
 * Author: zsw zswemail@qq.com
 */
class Test extends BaseController
{

    /**
     * @Route("test", method="GET")
     * @WikiItem("首页", description="首页详情")
     *
     * @AutoValidate({"username":"require|chsAlpha"}, message={"username":"请输入用户名"})
     * @Mock("username", mode="request", title="用户名", example="name")
     * @Mock("name", mode="response", title="名字", example="name", description="文章ID")
     */
    public function index($username){
        return "hello " . $username;
    }

    /**
     * @Route("mock", method="GET")
     * @WikiItem("详情", description="文章详情")
     *
     * @Mock("id", title="ID", example="numberBetween", description="文章ID")
     * @MockPack("articles", mode="response", title="文章列表", description="文章列表", limit=5)
     * @Mock("id", mode="response", title="ID", example="randomDigitNotNull", description="文章ID")
     * @Mock("title", mode="response", title="标题", example="name")
     * @Mock("create_time", mode="response", title="创建时间", example={"date": {"Y-m-d", "now"}})
     * @Mock("content", mode="response", title="内容", example={"sentence": 10})
     * @Mock("image", mode="response", title="图片", example="randomDigit")
     * @MockPack("user", main=true, mode="response", title="用户", description="发布者信息", limit=0)
     * @Mock("username", mode="response", title="用户名", example="name")
     * @MockPack("user")
     * @MockPack("articles")
     * @Mock("page", mode="response", title="页码", example="randomDigitNotNull", description="当前页码")
     */
    public function mock(){}
}

~~~

> 3、 访问路由、测试数据、接口文档

```
路由：/test?username=zsw

数据：/mock?mock=1

文档：/wiki
```


## 功能说明

### 1、注解路由
> 路由注解 模型注解 自动注入同Tp6官方注解

> 新增AutoValidate注解
~~~php
@AutoValidate({"username":"require|chsAlpha"}, message={"username":"请输入用户名"})
~~~

### 2、接口测试数据生成

> MockPack 嵌套数据生成支持 无限级嵌套
```
@MockPack extends MockBase
    // 数据长度 0表示单层数据
    @var int limit 
    
    /*
     * 置顶 （limit > 1 时有效)
     * false：['fields'=>[["a"=>"b"], ["aa"=>"bb"]]]
     * true：[["a"=>"b"], ["aa"=>"bb"]]
     */
    @var boolean main
```
例：
```
// MockPack(key)  MockPack中key相同值之间组成一层嵌套

/**
 * @MockPack("articles", mode="response", title="文章列表", description="文章列表", limit=3)
 *
 * @Mock("title", mode="response", title="标题", example="name")
 * @Mock("content", mode="response", title="内容", example={"sentence": 10})
 *
 * @MockPack("user", main=true, mode="response", title="用户", description="发布者信息", limit=0)
 * @Mock("username", mode="response", title="用户名", example="name")
 * @MockPack("user")
 * 
 * @MockPack("articles")
 */
 
生成结果

{
	"articles": [{
		"title": "乔阳",
		"content": "Vero impedit et consequatur quasi doloribus dolores illum sit expedita doloremque fugiat esse deleniti quisquam.",
		"user": {
			"username": "方建明"
		}
	}, {
		"title": "蒙桂花",
		"content": "Iure explicabo officiis minima et impedit sunt dignissimos necessitatibus ratione animi nam aperiam dolorum.",
		"user": {
			"username": "谷致远"
		}
	}, {
		"title": "郑文",
		"content": "Minus cum unde exercitationem sunt laudantium eveniet voluptatem magni ut cum non.",
		"user": {
			"username": "宁丽娟"
		}
	}]
}
```

> Mock 数据生成
```
@Mock extends MockBase
    /**
      * 自定义字符串 example="zsw"
      * Faker方法名 参考https://github.com/fzaninotto/Faker
      * 自定义方法名 \app\logic\Mock::name
      * example="方法名" || example={"方法名": {"参数1", "参数2"}}
      */
    @var string|array example
```

### 3、接口文档生成

```php
<?php
/**
 * @WikiMenu("测试")
 */
class Test extends BaseController
{
    /**
     * @Route("test", method="GET")
     * @WikiItem("首页", description="首页详情")
     * @Mock("name", mode="response", title="名字", example="name", description="名字")
     */
    public function index(){
        return "zsw";
    }
}
```


## 使用参考

### 1、注解路由

### 2、接口测试数据生成
~~~php
<?php
namespace app\controller;

use app\BaseController;
use iszsw\mock\annotation\illustrate\Route;
use iszsw\mock\annotation\illustrate\Mock;
use iszsw\mock\annotation\illustrate\MockPack;
use iszsw\mock\annotation\illustrate\WikiItem;
use iszsw\mock\annotation\illustrate\WikiMenu;

/**
 * @WikiMenu("测试")
 */
class Test extends BaseController
{
    /**
     * @Route("mock", method="GET")
     * @WikiItem("详情", description="文章详情")
     * @Mock("id", title="ID", example="numberBetween", description="文章ID")
     * 
     * @MockPack("articles", mode="response", title="文章列表", description="文章列表", limit=3)
     * @Mock("title", mode="response", title="标题", example="name")
     * @Mock("content", mode="response", title="内容", example={"sentence": 10})
     *
     * @MockPack("user", main=true, mode="response", title="用户", description="发布者信息", limit=0)
     * @Mock("username", mode="response", title="用户名", example="name")
     * @MockPack("user")
     * 
     * @MockPack("articles")
     */
    public function mock(){}
}
~~~

生成的数据格式为
```json
{
	"articles": [{
		"title": "乔阳",
		"content": "Vero impedit et consequatur quasi doloribus dolores illum sit expedita doloremque fugiat esse deleniti quisquam.",
		"user": {
			"username": "方建明"
		}
	}, {
		"title": "蒙桂花",
		"content": "Iure explicabo officiis minima et impedit sunt dignissimos necessitatibus ratione animi nam aperiam dolorum.",
		"user": {
			"username": "谷致远"
		}
	}, {
		"title": "郑文",
		"content": "Minus cum unde exercitationem sunt laudantium eveniet voluptatem magni ut cum non.",
		"user": {
			"username": "宁丽娟"
		}
	}]
}
```

### 3、接口文档生成
~~~php
<?php
namespace app\controller;

use iszsw\mock\annotation\illustrate\Route;
use iszsw\mock\annotation\illustrate\WikiItem;
use iszsw\mock\annotation\illustrate\WikiMenu;

/**
 * @WikiMenu("测试")
 */
class Test
{

    /**
     * @Route("test", method="GET")
     * @WikiItem("首页", description="首页详情")
     * @Mock("username", mode="request", title="用户名", example="name")
     * @Mock("name", mode="response", title="名字", example={"\app\controller\Mock::name": {100}}, description="文章ID")
     */
    public function index($username){}
}
~~~

![https://wx2.sbimg.cn/2020/07/09/CQkrN.png](https://wx2.sbimg.cn/2020/07/09/CQkrN.png)
