<?php

declare(strict_types=1);

namespace hsdrg\processor\adrg;

use hsdrg\struct\MedicalRecord;

/**
 * 单主手术或操作匹配的处理器
 * 
 * @author 王阮强 <wangruanqiang@hongshanhis.com>
 */
class SingleMajorProcedure extends Base
{
    /** @inheritDoc */
    public function detect(MedicalRecord $medicalRecord, array $items): bool
    {
        // 获取当前adrg下的主诊断数据，单诊断模式下只有表0
        $procedure = $items['procedure'][0] ?? [];
        return \in_array($medicalRecord->majorProcedure, $procedure);
    }
}
