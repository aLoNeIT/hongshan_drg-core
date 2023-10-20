<?php

declare(strict_types=1);

namespace hsdrg\processor\mdc;

use hsdrg\struct\MedicalRecord;

/**
 * 无分组匹配要求的处理器
 * 
 * @author 王阮强 <wangruanqiang@hongshanhis.com>
 */
class None extends Base
{
    /** @inheritDoc */
    public function detect(MedicalRecord $medicalRecord, array $items): bool
    {
        return true;
    }
}
