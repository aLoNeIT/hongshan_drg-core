<?php

declare(strict_types=1);

namespace hsdrg\processor\adrg;

use hsdrg\struct\MedicalRecord;

/**
 * 复杂处理器D
 * 
 * - 主要诊断+其他诊断1+主要手术或操作
 * - 其他诊断2+主要手术或操作
 * 
 * @author 王阮强 <wangruanqiang@hongshanhis.com>
 */
class ComplexD extends Base
{
    /** @inheritDoc */
    public function detect(MedicalRecord $medicalRecord, array $items): bool
    {
        return (
            \in_array($medicalRecord->principalDiagnosis, $items['diagnosis'][0] ?? [])
            && $this->detectDiagnosis($medicalRecord->secondaryDiagnosis, $items['diagnosis'][100] ?? [])
            && \in_array($medicalRecord->majorProcedure, $items['procedure'][0] ?? [])
        ) || (
            $this->detectDiagnosis($medicalRecord->secondaryDiagnosis, $items['diagnosis'][101] ?? [])
            && \in_array($medicalRecord->majorProcedure, $items['procedure'][0] ?? [])
        );
    }
}
