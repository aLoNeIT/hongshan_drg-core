<?php

declare(strict_types=1);

namespace hsdrg\processor\adrg;

use hsdrg\struct\MedicalRecord;

/**
 * 排除手术列表外的其他任意手术匹配的处理器
 * 
 * @author 王阮强 <wangruanqiang@hongshanhis.com>
 */
class ExcludeProcedure extends Base
{
    /** @inheritDoc */
    public function detect(MedicalRecord $medicalRecord, array $items): bool
    {
        // 主手术和其他手术合并为数组
        $procedures = [
            ...($medicalRecord->majorProcedure ? [$medicalRecord->majorProcedure] : []),
            ...$medicalRecord->secondaryProcedure
        ];
        // 计算交集
        $intersect = array_intersect($procedures, $items['procedure'][0] ?? []);
        // 再和手术集合计算差集
        $diff = array_diff($intersect, $procedures);
        // 如果无差集，说明手术都在排除列表中
        return !empty($diff);
    }
}
