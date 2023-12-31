<?php

declare(strict_types=1);

namespace hsdrg\processor\adrg;

use hsdrg\struct\MedicalRecord;

/**
 * 单主诊断匹配的处理器
 * 
 * @author 王阮强 <wangruanqiang@hongshanhis.com>
 */
class SinglePrincipalDiagnosis extends Base
{
    /** @inheritDoc */
    public function detect(MedicalRecord $medicalRecord, array $items): bool
    {
        $procedure = [
            ...($medicalRecord->majorProcedure ? [$medicalRecord->majorProcedure] : []),
            ...$medicalRecord->secondaryProcedure
        ];
        if (!empty($procedure)) {
            // 有手术，不入组
            return false;
        }
        // 获取当前adrg下的主诊断数据，单诊断模式下只有表0
        $diagnosis = $items['diagnosis'][0] ?? [];
        return \in_array($medicalRecord->principalDiagnosis, $diagnosis);
    }
}
