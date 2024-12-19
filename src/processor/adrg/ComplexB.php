<?php

declare(strict_types=1);

namespace hsdrg\processor\adrg;

use hsdrg\struct\MedicalRecord;

/**
 * 复杂处理器B
 * 
 * - 主要诊断+手术或操作1+手术或操作2
 * - 主要诊断+手术或操作1+手术或操作3+手术或操作4
 * - 主要诊断+手术或操作4+手术或操作5
 * 
 * @author 王阮强 <wangruanqiang@hongshanhis.com>
 */
class ComplexB extends Base
{
    /** @inheritDoc */
    public function detect(MedicalRecord $medicalRecord, array $items): bool
    {
        // 先匹配主要诊断
        if (!\in_array($medicalRecord->principalDiagnosis, $items['diagnosis'][0] ?? [])) {
            return false;
        }
        $procedures = [
            ...($medicalRecord->majorProcedure ? [$medicalRecord->majorProcedure] : []),
            ...$medicalRecord->secondaryProcedure
        ];
        // 三个其中一个匹配成功即匹配成功
        return $this->detectMultiProcedure($procedures, $items, [0, 1])
            || $this->detectMultiProcedure($procedures, $items, [0, 2, 3])
            || $this->detectMultiProcedure($procedures, $items, [0, 3, 4]);
    }
}
