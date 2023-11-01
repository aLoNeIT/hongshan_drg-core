<?php

declare(strict_types=1);

namespace hsdrg\processor\mdc;

use hsdrg\struct\MedicalRecord;

/**
 * 所有诊断匹配到两组数据内的处理器
 * 
 * @author 王阮强 <wangruanqiang@hongshanhis.com>
 */
class TwiceDiagnosis extends Base
{
    /** @inheritDoc */
    public function detect(MedicalRecord $medicalRecord, array $items): bool
    {
        // 主诊断和其他诊断合并为一个数组
        $diagnosis = [
            $medicalRecord->principalDiagnosis,
            ...$medicalRecord->secondaryDiagnosis
        ];
        $matchCount = 0;
        foreach ($items['diagnosis'] as $item) {
            // 计算交集
            $intersect = array_intersect($diagnosis, $item);
            if (!empty($intersect)) {
                $matchCount++;
                // 从诊断集合中删除当前诊断
                $diagnosis = array_diff($diagnosis, $intersect);
                continue;
            }
        }
        return $matchCount >= 2;
    }
}
