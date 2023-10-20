<?php

declare(strict_types=1);

namespace hsdrg\processor\adrg;

use hsdrg\struct\MedicalRecord;

/**
 * 无手术的匹配的处理器
 */
class NoProcedure extends Base
{
    /** @inheritDoc */
    public function detect(MedicalRecord $medicalRecord, array $items): bool
    {
        if ((\is_null($medicalRecord->majorProcedure)
                || '' === $medicalRecord->majorProcedure)
            && empty($medicalRecord->secondaryProcedure)
        ) {
            return true;
        }
        return false;
    }
}
