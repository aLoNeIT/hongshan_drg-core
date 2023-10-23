<?php

declare(strict_types=1);

namespace hsdrg\interfaces;

use hsdrg\struct\MedicalRecord;

/**
 * drg处理器接口
 * 
 * @author 王阮强 <wangruanqiang@hongshanhis.com>
 */
interface ICollection
{
    /**
     * 加载子数据
     *
     * @param array $data 子项数据
     * @return static 返回当前对象
     */
    public function loadItems(array $data): self;
}
