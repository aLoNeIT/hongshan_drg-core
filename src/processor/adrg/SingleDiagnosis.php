<?php

declare(strict_types=1);

namespace hsdrg\processor\adrg;

use hsdrg\struct\MedicalRecord;

/**
 * 单诊断匹配的处理器
 * 
 * @author 王阮强 <wangruanqiang@hongshanhis.com>
 */
class SingleDiagnosis extends Base
{
    /** @inheritDoc */
    public function detect(MedicalRecord $medicalRecord, array $items): bool
    {
        // 主诊断和其他诊断合并成新数组
        $diagnosis = [
            ...($medicalRecord->principalDiagnosis ? [$medicalRecord->principalDiagnosis] : []),
            ...$medicalRecord->secondaryDiagnosis
        ];
        // 计算交集
        $intersect = array_intersect($diagnosis, $items['diagnosis'][0] ?? []);
        return !empty($intersect);
    }
}
