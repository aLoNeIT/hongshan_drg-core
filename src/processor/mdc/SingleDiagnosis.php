<?php

declare(strict_types=1);

namespace hsdrg\processor\mdc;

use hsdrg\struct\MedicalRecord;

/**
 * 单诊断匹配的处理器
 * 
 * @author 王阮强 <wangruanqiang@hongshanhis.com>
 */
class SingleDiagnosis extends Base
{
    /** @inheritDoc */
    public function detect(MedicalRecord $medicalRecord, array $items): bool
    {
        return true;
    }
}
