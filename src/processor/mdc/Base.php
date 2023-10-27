<?php

declare(strict_types=1);

namespace hsdrg\processor\mdc;

use hsdrg\interfaces\IDetectProcessor;
use hsdrg\processor\Base as BaseProcessor;

/**
 * MDC入组检测处理器基类
 * 
 * - 无要求
 * - 主诊断单匹配
 * - 所有诊断，匹配到一个
 * - 所有诊断分多组，匹配两组
 * 
 * @author 王阮强 <wangruanqiang@hongshanhis.com>
 */
abstract class Base extends BaseProcessor
{
}
