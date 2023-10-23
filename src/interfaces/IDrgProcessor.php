<?php

declare(strict_types=1);

namespace hsdrg\interfaces;

use hsdrg\struct\MedicalRecord;

/**
 * drg处理器接口
 * 
 * @author 王阮强 <wangruanqiang@hongshanhis.com>
 */
interface IDRGProcessor
{
    /**
     * drg分组处理
     * 
     * @param MedicalRecord $medicalRecord 病案信息
     * @return array 返回JsonTable格式的数组数据，msg节点是匹配到的编码
     */
    public function process(MedicalRecord $medicalRecord): array;
}
