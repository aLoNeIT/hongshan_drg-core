<?php

declare(strict_types=1);

namespace hsdrg\interfaces;

use hsdrg\struct\MedicalRecord;

/**
 * adrg处理器接口
 * 
 * @author 王阮强 <wangruanqiang@hongshanhis.com>
 */
interface IMDCProcessor
{
    /**
     * 根据病案信息和入组依据数据匹配对应分组
     *
     * @param MedicalRecord $medicalRecord 病案信息
     * @param array $items 入组依据数据
     * @return boolean 返回匹配成功或失败
     */
    public function detect(MedicalRecord $medicalRecord, array $items): bool;
}
