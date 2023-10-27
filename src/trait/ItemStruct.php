<?php

declare(strict_types=1);

namespace hsdrg\trait;

use hsdrg\Util;

/**
 * 子项数据结构
 * 
 * @author 王阮强 <wangruanqiang@hongshanhis.com>
 */
trait ItemStruct
{
    /**
     * 类型
     *
     * @var integer|null
     */
    public $type = null;
    /**
     * 索引下表
     *
     * @var integer|null
     */
    public $index = null;
}
