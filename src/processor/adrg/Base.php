<?php

declare(strict_types=1);

namespace hsdrg\processor\adrg;

use hsdrg\interfaces\IDetectProcessor;
use hsdrg\processor\Base as BaseProcessor;

/**
 * ADRG处理器基类
 * 
 * - 单主诊断
 * - 单主手术或操作
 * - 双手术，同时包含A组和B组手术，AC1、FB1
 * - 单主诊断+多手术，包含主诊断+手术表1+手术表2或者主诊断+手术表1+手术表3+手术表4，JA1、JA2
 * - 单诊断（主诊断或其他诊断符合）
 * - 任意手术（只要存在手术即可），XJ1、TB1、SB1
 * - 任意手术（排除指定手术列表），WJ1
 * - 无手术
 * - 包含主要诊断或主要操作，RE1、RG1
 * - 单主诊断和双手术组合（手术需要匹配手术表1和手术表2），IB1
 * - 单主诊断和单主手术,BB1
 * - 单主诊断+多手术简易版，包含主诊断+手术表1或者主诊断+手术表2+手术表3，、NA1
 * 
 * @author 王阮强 <wangruanqiang@hongshanhis.com>
 */
abstract class Base extends BaseProcessor
{
    protected function detectDiagnosis(array &$diagnosises, array $item): bool
    {
        // 计算交集
        $intersect = array_intersect($diagnosises, $item);
        // 无交集则不匹配，直接退出
        if (empty($intersect)) {
            return false;
        }
        // 计算差集，进行下一次计算
        $diagnosises = \array_diff($diagnosises, $intersect);
        return true;
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
    /**
     * 匹配多手术
     *
     * @param array $procedures 患者手术列表
     * @param array $items 入组依据数据
     * @param array $index 手术索引
     * @return boolean 返回匹配结果
     */
    protected function detectMultiProcedure(array $procedures, array $items, array $index): bool
    {
        $match = true;
        foreach ($index as $idx) {
            if ($this->detectProcedure($procedures, $items['procedure'][$idx] ?? [])) {
                $match = false;
                break;
            }
        }
        return $match;
    }
}
