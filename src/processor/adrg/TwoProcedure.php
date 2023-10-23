<?php

declare(strict_types=1);

namespace hsdrg\processor\adrg;

use hsdrg\struct\MedicalRecord;

/**
 * 双手术组合匹配的处理器
 * 
 * @author 王阮强 <wangruanqiang@hongshanhis.com>
 */
class TwoProcedure extends SinglePrincipalDiagnosisAndMultiProcedure
{
    /** @inheritDoc */
    public function detect(MedicalRecord $medicalRecord, array $items): bool
    {
        // 主手术和其他手术合并为数组
        $procedures = [
            ...($medicalRecord->majorProcedure ? [$medicalRecord->majorProcedure] : []),
            ...$medicalRecord->secondaryProcedure
        ];
        $match = true;
        // 手术表1+手术表2匹配
        foreach ([0, 1] as $idx) {
            if (!$this->detectProcedure($procedures, $items['procedure'][$idx] ?? [])) {
                $match = false;
                break;
            }
        }
        return $match;
    }
}
