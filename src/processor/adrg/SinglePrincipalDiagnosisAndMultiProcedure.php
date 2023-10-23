<?php

declare(strict_types=1);

namespace hsdrg\processor\adrg;

use hsdrg\struct\MedicalRecord;

/**
 * 单主诊断+手术组合匹配的处理器
 * 
 * @author 王阮强 <wangruanqiang@hongshanhis.com>
 */
class SinglePrincipalDiagnosisAndMultiProcedure extends Base
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
        // 手术表1+手术表2匹配
        foreach ([0, 1] as $idx) {
            if (!$this->detectProcedure($procedures, $items['procedure'][$idx] ?? [])) {
                $match = false;
                break;
            }
        }
        if (false === $match) {
            $match = true;
            // 继续匹配手术表1+手术表3+手术表4
            foreach ([0, 2, 3] as $idx) {
                if (!$this->detectProcedure($procedures, $items['procedure'][$idx] ?? [])) {
                    $match = false;
                    break;
                }
            }
        }
        return $match;
    }
    /**
     * 匹配手术
     *
     * @param array $procedures 患者手术列表
     * @param array $item 当前ADRG需要匹配的手术列表
     * @return boolean 返回匹配结果
     */
    protected function detectProcedure(array &$procedures, array $item): bool
    {
        // 计算交集
        $intersect = array_intersect($procedures, $item);
        // 无交集则不匹配，直接退出
        if (empty($intersect)) {
            return false;
        }
        // 计算差集，进行下一次计算
        $procedures = \array_diff($procedures, $intersect);
        return true;
    }
}
