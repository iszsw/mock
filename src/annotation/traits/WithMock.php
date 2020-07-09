<?php

namespace iszsw\mock\annotation\traits;

use Faker\Factory;

/**
 * faker生成器
 * Trait WithMock
 *
 * @package iszsw\mock\annotation\traits
 * Author: zsw zswemail@qq.com
 */
trait WithMock
{

    private $faker;

    protected function getFaker()
    {
        if ( ! $this->faker ){
            $this->faker = Factory::create('zh_CN');
        }
        return $this->faker;
    }

    protected function mock($method, array $params = [])
    {
        try{
            if (strpos($method, "::")) {
                $function = explode('::', $method, 2);
            }else{
                $function = [$this->getFaker(), $method];
            }
            $value = call_user_func_array($function, $params);
        }catch (\Exception $e) {
            $value = $method;
        }
        return $value;
    }

}