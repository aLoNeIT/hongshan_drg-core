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
 * - 双手术，同时包含A组和B组手术
 * - 单主诊断+多手术，包含主诊断+手术表1+手术表2或者主诊断+手术表1+手术表3+手术表4
 * - 单诊断（主诊断或其他诊断符合）
 * - 任意手术（只要存在手术即可）
 * - 任意手术（排除指定手术列表）
 * - 无手术
 * @author 王阮强 <wangruanqiang@hongshanhis.com>
 */
abstract class Base extends BaseProcessor implements IDetectProcessor
{
}
