<?php

declare(strict_types=1);

namespace hsdrg\processor\adrg;

use hsdrg\struct\MedicalRecord;

/**
 * 复杂处理器C
 * 
 * - 主要诊断1+主要手术或操作
 * - 主要诊断2+其他诊断+主要手术或操作
 * 
 * @author 王阮强 <wangruanqiang@hongshanhis.com>
 */
class ComplexC extends Base
{
    /** @inheritDoc */
    public function detect(MedicalRecord $medicalRecord, array $items): bool
    {
        $processor = new SinglePrincipalDiagnosisAndMajorProcedure();
        // 主要诊断1+主要手术或操作
        if ($processor->detect($medicalRecord, $items)) {
            return true;
        }
        // 主要诊断2+其他诊断+主要手术或操作
        return \in_array($medicalRecord->principalDiagnosis, $items['diagnosis'][1] ?? [])
            && $this->detectDiagnosis($medicalRecord->secondaryDiagnosis, $items['diagnosis'][100] ?? [])
            && \in_array($medicalRecord->majorProcedure, $items['procedure'][0] ?? []);
    }
}
