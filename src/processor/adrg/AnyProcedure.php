<?php

declare(strict_types=1);

namespace hsdrg\processor\adrg;

use hsdrg\struct\MedicalRecord;

/**
 * 任意手术匹配的处理器
 * 
 * @author 王阮强 <wangruanqiang@hongshanhis.com>
 */
class AnyProcedure extends Base
{
    /** @inheritDoc */
    public function detect(MedicalRecord $medicalRecord, array $items): bool
    {
        if (
            (!\is_null($medicalRecord->majorProcedure) && '' != $medicalRecord->secondaryProcedure)
            || (!empty($medicalRecord->secondaryProcedure))
        ) {
            return true;
        }
        return false;
    }
}
