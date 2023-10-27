<?php

declare(strict_types=1);

namespace hsdrg\interfaces;

use hsdrg\struct\MedicalRecord;

/**
 * 子数据集合接口
 * 
 * @author 王阮强 <wangruanqiang@hongshanhis.com>
 */
interface IChildCollection
{
    /**
     * 加载子数据
     *
     * @param array $data 子项数据，每个元素都是一个Collection对象
     * @param boolean $clear 是否清理已有的数据，默认true
     * @return static 返回当前对象
     */
    public function loadChildren(array $data, bool $clear = true): static;
}
