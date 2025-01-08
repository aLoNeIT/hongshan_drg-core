<?php

declare(strict_types=1);

namespace hsdrg\processor\adrg;

use hsdrg\struct\MedicalRecord;

/**
 * 复杂处理器A
 * 
 * - 主要诊断+主要手术或操作1
 * - 主要手术或操作2
 * - 手术或操作3+手术或操作4
 * 
 * @author 王阮强 <wangruanqiang@hongshanhis.com>
 */
class ComplexA extends Base
{
    /** @inheritDoc */
    public function detect(MedicalRecord $medicalRecord, array $items): bool
    {
        // 三个其中一个匹配成功即匹配成功
        return $this->detectPrincipalDiagnosisAndMajorProcedure($medicalRecord, $items)
            || $this->detectMajorProcedure2($medicalRecord, $items)
            || $this->detectProcedure3And4($medicalRecord, $items);
    }
    /**
     * 主诊断+主手术
     *
     * @param MedicalRecord $medicalRecord 患者病历
     * @param array $items 入组依据数据
     * @return boolean 返回匹配结果
     */
    protected function detectPrincipalDiagnosisAndMajorProcedure(MedicalRecord $medicalRecord, array $items): bool
    {
        // 先匹配主要诊断
        if (!\in_array($medicalRecord->principalDiagnosis, $items['diagnosis'][0] ?? [])) {
            return false;
        }
        // 在匹配主要手术或操作
        if (!\in_array($medicalRecord->majorProcedure, $items['procedure'][0] ?? [])) {
            return false;
        }
        return true;
    }
    /**
     * 主要手术或操作2
     *
     * @param MedicalRecord $medicalRecord 患者病历
     * @param array $items 入组依据数据
     * @return boolean 返回匹配结果
     */
    protected function detectMajorProcedure2(MedicalRecord $medicalRecord, array $items): bool
    {
        return \in_array($medicalRecord->majorProcedure, $items['procedure'][1] ?? []);
    }
    /**
     * 手术或操作3+手术或操作4
     *
     * @param MedicalRecord $medicalRecord 患者病历
     * @param array $items 入组依据数据
     * @return boolean 返回匹配结果
     */
    protected function detectProcedure3And4(MedicalRecord $medicalRecord, array $items): bool
    {
        $procedures = [
            ...($medicalRecord->majorProcedure ? [$medicalRecord->majorProcedure] : []),
            ...$medicalRecord->secondaryProcedure
        ];
        $match = true;
        foreach ([2, 3] as $idx) {
            if (!$this->detectProcedure($procedures, $items['procedure'][$idx] ?? [])) {
                $match = false;
                break;
            }
        }
        return $match;
    }
}
