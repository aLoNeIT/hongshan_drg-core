<?php

declare(strict_types=1);
/**
 * 本文件是所有的测试数据
 */
return [
    [
        // 单诊断带严重并发症
        'drg_code' => 'GE19',
        'medical_record' => [
            'code' => '1',
            'sex' => 2,
            'age' => 30,
            'in_days' => 15,
            'out_type' => 1,
            'principal_diagnosis' => 'C18.100',
            'secondary_diagnosis' => [],
            'major_procedure' => '47.0901',
            'secondary_procedure' => []
        ]
    ],
    [
        // 单诊断带严重并发症
        'drg_code' => 'JB19',
        'medical_record' => [
            'code' => '0100000808',
            'sex' => 1,
            'age' => 32,
            'in_days' => 2,
            'out_type' => 1,
            'principal_diagnosis' => 'C44.501',
            'secondary_diagnosis' => [],
            'major_procedure' => '85.5300x001',
            'secondary_procedure' => []
        ]
    ],
    [
        // 单诊断带严重并发症
        'drg_code' => 'IB19',
        'medical_record' => [
            'code' => '0100000809',
            'sex' => 1,
            'age' => 32,
            'in_days' => 2,
            'out_type' => 1,
            'principal_diagnosis' => 'A18.000x047+M49.0*',
            'secondary_diagnosis' => [],
            'major_procedure' => '03.0900x025',
            'secondary_procedure' => []
        ]
    ],
];
