<?php

declare(strict_types=1);

namespace hsdrg\struct;

use hsdrg\Util;

/**
 * 结构体基类
 * 
 * @author 王阮强 <wangruanqiang@hongshanhis.com>
 */
abstract class Base
{
    /**
     * 构造函数
     */
    public function __construct()
    {
        $this->initialize();
    }
    /**
     * 初始化函数
     *
     * @return void
     */
    protected function initialize(): void
    {
    }
    /**
     * 当前对象公共属性值转数组
     *
     * @return array 返回仅包含公共属性的数组
     */
    public function toArray(): array
    {
        return Util::getPublicProps($this);
    }
}
