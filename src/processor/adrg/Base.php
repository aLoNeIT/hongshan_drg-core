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
 * - 单主诊断+多手术，包含主诊断+手术表1+手术表2或者主诊断+手术表1+手术表3+手术表4，JA1、JA2、NA1
 * - 单诊断（主诊断或其他诊断符合）
 * - 任意手术（只要存在手术即可），XJ1、TB1、SB1
 * - 任意手术（排除指定手术列表），WJ1
 * - 无手术
 * - 包含主要诊断或主要操作，RE1、RG1
 * - 单主诊断和双手术组合（手术需要匹配手术表1和手术表2），IB1
 * - 单主诊断和单主手术,BB1
 * 
 * @author 王阮强 <wangruanqiang@hongshanhis.com>
 */
abstract class Base extends BaseProcessor
{
}
