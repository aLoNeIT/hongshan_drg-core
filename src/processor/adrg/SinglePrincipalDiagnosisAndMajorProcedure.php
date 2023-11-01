<?php

declare(strict_types=1);

namespace hsdrg\processor\adrg;

use hsdrg\struct\MedicalRecord;

/**
 * 包含单主诊断和单主手术
 * 
 * @author 王阮强 <wangruanqiang@hongshanhis.com>
 */
class SinglePrincipalDiagnosisAndMajorProcedure extends Base
{
    /** @inheritDoc */
    public function detect(MedicalRecord $medicalRecord, array $items): bool
    {
        $diagnosis = $items['diagnosis'][0] ?? [];
        $proceudre = $items['procedure'][0] ?? [];
        return \in_array($medicalRecord->principalDiagnosis, $diagnosis)
            && \in_array($medicalRecord->majorProcedure, $proceudre);
    }
}
