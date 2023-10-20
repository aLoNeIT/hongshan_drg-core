<?php

declare(strict_types=1);

namespace hsdrg\processor\mdc;

use hsdrg\struct\MedicalRecord;

/**
 * 所有诊断匹配到两组数据内的处理器
 * 
 * @author 王阮强 <wangruanqiang@hongshanhis.com>
 */
class TwiceDiagnosis extends Base
{
    /** @inheritDoc */
    public function detect(MedicalRecord $medicalRecord, array $items): bool
    {
        return true;
    }
}
