<?php

declare(strict_types=1);

namespace hsdrg\processor;

use hsdrg\struct\MedicalRecord;

/**
 * adrg单手术处理器，病案信息中的主手术进行判断
 * 
 * @author 王阮强 <wangruanqiang@hongshanhis.com>
 */
class SignleProcedure extends Base
{
    /** @inheritDoc */
    public function detect(MedicalRecord $medicalRecord, array $adrgItems): bool
    {
        // 获取当前adrg下的主诊断数据，单诊断模式下只有表0
        $diagnosis = $adrgItems['diagnosis'][0] ?? [];
        return isset($diagnosis[$medicalRecord->principalDiagnosis]);
    }
}
