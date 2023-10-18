<?php

declare(strict_types=1);

namespace hsdrg\processor;

use hsdrg\struct\MedicalRecord;

/**
 * adrg单诊断处理器，使用病案中的主诊断进行判断
 * 
 * @author 王阮强 <wangruanqiang@hongshanhis.com>
 */
class SignleDiagnosis extends Base
{
    /** @inheritDoc */
    public function detect(MedicalRecord $medicalRecord, array $adrgItems): bool
    {
        // 获取当前adrg下的主诊断数据，单诊断模式下只有表0
        $diagnosis = $adrgItems['diagnosis'][0] ?? [];
        return isset($diagnosis[$medicalRecord->principalDiagnosis]);
    }
}
