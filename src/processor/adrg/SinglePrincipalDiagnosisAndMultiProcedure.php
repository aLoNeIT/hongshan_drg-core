<?php

declare(strict_types=1);

namespace hsdrg\processor\adrg;

use hsdrg\struct\MedicalRecord;

/**
 * 单主诊断+手术组合匹配的处理器
 * 
 * @author 王阮强 <wangruanqiang@hongshanhis.com>
 */
class SinglePrincipalDiagnosisAndMultiProcedure extends SinglePrincipalDiagnosisAndTwoProcedure
{
    /** @inheritDoc */
    public function detect(MedicalRecord $medicalRecord, array $items): bool
    {
        // 父类先执行匹配
        $match = parent::detect($medicalRecord, $items);
        if (false === $match) {
            $match = true;
            // 继续匹配手术表1+手术表3+手术表4
            $procedures = [
                ...($medicalRecord->majorProcedure ? [$medicalRecord->majorProcedure] : []),
                ...$medicalRecord->secondaryProcedure
            ];
            foreach ([0, 2, 3] as $idx) {
                if (!$this->detectProcedure($procedures, $items['procedure'][$idx] ?? [])) {
                    $match = false;
                    break;
                }
            }
        }
        return $match;
    }
}
