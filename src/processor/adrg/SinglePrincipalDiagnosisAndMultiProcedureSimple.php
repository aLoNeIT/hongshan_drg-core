<?php

declare(strict_types=1);

namespace hsdrg\processor\adrg;

use hsdrg\struct\MedicalRecord;

/**
 * 单主诊断+(手术表1 | 手术表2+手术表3)
 * 
 * @author 王阮强 <wangruanqiang@hongshanhis.com>
 */
class SinglePrincipalDiagnosisAndMultiProcedureSimple extends SinglePrincipalDiagnosisAndMultiProcedure
{
    /** @inheritDoc */
    public function detect(MedicalRecord $medicalRecord, array $items): bool
    {
        // 先匹配主要诊断
        if (!\in_array($medicalRecord->principalDiagnosis, $items['diagnosis'][0] ?? [])) {
            return false;
        }
        // 主手术和其他手术合并为数组
        $procedures = [
            ...($medicalRecord->majorProcedure ? [$medicalRecord->majorProcedure] : []),
            ...$medicalRecord->secondaryProcedure
        ];
        $match = true;
        $p = $procedures;
        // 手术表1匹配
        foreach ([0] as $idx) {
            if (!$this->detectProcedure($p, $items['procedure'][$idx] ?? [])) {
                $match = false;
                break;
            }
        }
        if (false === $match) {
            $match = true;
            $p = $procedures;
            // 继续匹配手术表2+手术表3
            foreach ([1, 2] as $idx) {
                if (!$this->detectProcedure($p, $items['procedure'][$idx] ?? [])) {
                    $match = false;
                    break;
                }
            }
        }
        return $match;
    }
}
