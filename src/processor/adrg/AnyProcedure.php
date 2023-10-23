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
        // 主手术和其他手术合并为数组
        $procedures = [
            ...($medicalRecord->majorProcedure ? [$medicalRecord->majorProcedure] : []),
            ...$medicalRecord->secondaryProcedure
        ];
        return !empty($procedures);
    }
}
